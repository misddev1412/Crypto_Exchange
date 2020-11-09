<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\RoleRequest;
use App\Models\Core\Role;
use App\Services\Core\DataTableService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;


class RoleController extends Controller
{
    public function index(): View
    {
        $searchFields = [
            ['name', __('Role Name')],
        ];
        $orderFields = [
            ['id', __('Serial')],
            ['name', __('Role Name')],
        ];

        $queryBuilder = Role::orderBy('created_at', 'desc');
        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->create($queryBuilder);
        $data['title'] = __('Role Management');
        $data['defaultRoles'] = config('commonconfig.fixed_roles');
        if (!is_array($data['defaultRoles'])) {
            $data['defaultRoles'] = [];
        }

        return view('core.roles.index', $data);
    }

    public function create(): View
    {
        $data['routes'] = config('webpermissions.configurable_routes');
        $data['title'] = __('Create User Role');

        return view('core.roles.create', $data);
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        $parameters = [
            'name' => $request->name,
            'permissions' => $request->roles
        ];

        $parameters['accessible_routes'] = build_permission($request->roles);

        if ($role = Role::create($parameters)) {
            Cache::forever("roles_{$role->slug}", $parameters['accessible_routes']);
            return redirect()->route('roles.edit', $role->slug)->with(RESPONSE_TYPE_SUCCESS, __('User role has been created successfully.'));
        }

        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to create user role.'));
    }

    public function edit(Role $role): View
    {
        $data['role'] = $role;
        $data['routes'] = config('webpermissions.configurable_routes');

        $data['title'] = __('Edit Role: :role', ['role' => $role->name]);

        return view('core.roles.edit', $data);
    }

    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        $roles = $request->roles;

        $parameters = [
            'permissions' => $roles
        ];

        $parameters['accessible_routes'] = build_permission($roles, $role->slug);

        if ($role->update($parameters)) {
            return redirect()->route('roles.edit', $role->slug)->with(RESPONSE_TYPE_SUCCESS, __('User role has been updated successfully.'));
        }

        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to update user role.'));
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($this->isNonDeletableRole($role->slug)) {
            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('This role cannot be deleted.'));
        }

        $userCount = $role->users->count();

        $deleted = false;
        if ($userCount <= 0) {
            $deleted = $role->delete();
        }

        if ($deleted) {
            return redirect()->route('roles.index')->with(RESPONSE_TYPE_SUCCESS, __('User role has been deleted successfully.'));
        }

        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('This role cannot be deleted.'));
    }

    private function isNonDeletableRole(string $slug): bool
    {
        $defaultRoles = config('commonconfig.fixed_roles');
        return in_array($slug, $defaultRoles);
    }

    public function changeStatus(Role $role): RedirectResponse
    {
        if ($this->isNonDeletableRole($role->slug)) {
            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('User role status can not be changed.'));
        }

        if ($role->toggleStatus()) {
            return redirect()->route('roles.index')->with(RESPONSE_TYPE_SUCCESS, __('User role has been changed successfully.'));
        }

        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('User role status can not be changed.'));
    }
}
