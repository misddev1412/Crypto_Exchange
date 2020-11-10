<?php

namespace Database\Seeders;

use App\Models\Core\User;
use App\Models\Core\UserPreference;
use App\Models\Core\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminUser = User::superAdmin()->first();

        if (empty($adminUser)) {
            User::factory()->create([
                'assigned_role' => USER_ROLE_ADMIN,
                'username' => 'superadmin',
                'email' => 'superadmin@codemen.org',
                'password' => Hash::make('superadmin'),
                'is_accessible_under_maintenance' => ACTIVE,
                'is_email_verified' => VERIFIED,
                'is_super_admin' => ACTIVE,
                'status' => STATUS_ACTIVE,
            ])->each(function ($superadmin) {
                $superadmin->profile()->save(
                    UserProfile::factory()->make()
                );
                $superadmin->preference()->save(
                    UserPreference::factory()->make()
                );
            });
        }

        User::factory()->create([
            'assigned_role' => USER_ROLE_ADMIN,
            'username' => 'admin',
            'email' => 'admin@codemen.org',
            'password' => Hash::make('password'),
            'is_accessible_under_maintenance' => ACTIVE,
            'is_email_verified' => VERIFIED,
            'is_super_admin' => INACTIVE,
            'status' => STATUS_ACTIVE,
        ])->each(function ($admin) {
            $admin->profile()->save(
                UserProfile::factory()->make()
            );
            $admin->preference()->save(
                UserPreference::factory()->make()
            );
        });

        User::factory()->create([
            'username' => 'user',
            'email' => 'user@codemen.org',
            'password' => Hash::make('password'),
            'is_accessible_under_maintenance' => INACTIVE,
            'is_email_verified' => VERIFIED,
            'is_super_admin' => INACTIVE,
            'status' => STATUS_ACTIVE,
        ])->each(function ($user) {
            $user->profile()->save(
                UserProfile::factory()->make()
            );
            $user->preference()->save(
                UserPreference::factory()->make()
            );
        });



        User::factory()->count(10)->create([
            'password' => Hash::make('password'),
        ])->each(function ($allUser) {
            $allUser->profile()->save(
                UserProfile::factory()->make()
            );
            $allUser->preference()->save(
                UserPreference::factory()->make()
            );
        });
    }
}
