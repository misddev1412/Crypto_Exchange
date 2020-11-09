<?php

namespace App\Http\Controllers\KycManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kyc\AdminKycReasonRequest;
use App\Models\Core\Notification;
use App\Models\Core\Role;
use App\Models\Kyc\KycVerification;
use App\Services\Core\DataTableService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminKycController extends Controller
{
    public function index(): View
    {
        $searchFields = [
            ['email', __('Email')],
        ];

        $orderFields = [
            ['email', __('Email')],
        ];


        $filterFields = [
            ['status', __('Status'), kyc_status()],
        ];

        $queryBuilder = KycVerification::with('user')
            ->orderByDesc('created_at');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->setFilterFields($filterFields)
            ->create($queryBuilder);

        $data['title'] = __('KYC Management');
        return view('kyc_management.admin.index', $data);
    }

    public function show($id): View
    {
        $data['verification'] = KycVerification::where('id', $id)->firstOrFail();
        $data['title'] = __('View KYC Verification Request');

        return view('kyc_management.admin.show', $data);
    }
}
