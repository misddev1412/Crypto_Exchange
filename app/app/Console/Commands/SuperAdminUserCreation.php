<?php

namespace App\Console\Commands;

use App\Models\Core\User;
use App\Services\Core\UserService;
use Exception;
use Illuminate\Console\Command;

class SuperAdminUserCreation extends Command
{
    protected $signature = 'make:superadmin';

    protected $description = 'This command will create a superadmin user if the user does not exists';

    public function handle()
    {
        $user = User::where('is_super_admin', ACTIVE)->first();

        if (!empty($user)) {
            $this->error("The superadmin user already exists. System does not allow to create multiple superadmin user.");
            return;
        }
        $params['first_name'] = null;
        while (empty($params['first_name'])) {
            $params['first_name'] = $this->ask('First name of the superadmin?');
        }

        $params['last_name'] = null;
        while (empty($params['last_name'])) {
            $params['last_name'] = $this->ask('Last name of the superadmin?');
        }

        $params['username'] = null;
        while (empty($params['username'])) {
            $params['username'] = $this->ask('Username of the superadmin?');
        }

        $params['email'] = null;
        while (empty($params['email'])) {
            $params['email'] = $this->ask('Email of the superadmin?');
        }

        do {
            $params['password'] = $this->secret('Password of the superadmin?');
            $confirmPassword = $this->secret('Retype password of the superadmin?');
        } while (empty($params['password']) || empty($confirmPassword) || $params['password'] !== $confirmPassword);

        $params['is_super_admin'] = ACTIVE;
        $params['is_email_verified'] = ACTIVE;
        $params['is_financial_active'] = ACTIVE;
        $params['is_accessible_under_maintenance'] = ACTIVE;
        $params['status'] = STATUS_ACTIVE;
        $params['assigned_role'] = USER_ROLE_ADMIN;

        $user = User::where(function ($query) use ($params) {
            $query->where('username', $params['username'])
                ->orWhere('email', $params['email']);
        })->first();

        if (!empty($user)) {
            $this->error("Username/Email already exists.");
            return;
        }

        try {
            app(UserService::class)->generate($params);
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
            return;
        }
        $this->info("Superadmin has been created successfully.");
        return;
    }
}
