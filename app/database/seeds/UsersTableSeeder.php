<?php

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
            factory(User::class)->create([
                'assigned_role' => USER_ROLE_ADMIN,
                'username' => 'superadmin',
                'email' => 'superadmin@codemen.org',
                'password' => Hash::make('superadmin'),
                'is_accessible_under_maintenance' => ACTIVE,
                'is_email_verified' => VERIFIED,
                'is_super_admin' => ACTIVE,
                'status' => STATUS_ACTIVE,
            ])->each(function ($superadmin) {
                $superadmin->profile()->save(factory(UserProfile::class)->make());
                $superadmin->preference()->save(factory(UserPreference::class)->make());
            });
        }

        factory(User::class)->create([
            'assigned_role' => USER_ROLE_ADMIN,
            'username' => 'admin',
            'email' => 'admin@codemen.org',
            'password' => Hash::make('password'),
            'is_accessible_under_maintenance' => ACTIVE,
            'is_email_verified' => VERIFIED,
            'is_super_admin' => INACTIVE,
            'status' => STATUS_ACTIVE,
        ])->each(function ($admin) {
            $admin->profile()->save(factory(UserProfile::class)->make());
            $admin->preference()->save(factory(UserPreference::class)->make());
        });

        factory(User::class)->create([
            'username' => 'user',
            'email' => 'user@codemen.org',
            'password' => Hash::make('password'),
            'is_accessible_under_maintenance' => INACTIVE,
            'is_email_verified' => VERIFIED,
            'is_super_admin' => INACTIVE,
            'status' => STATUS_ACTIVE,
        ])->each(function ($user) {
            $user->profile()->save(factory(UserProfile::class)->make());
            $user->preference()->save(factory(UserPreference::class)->make());
        });



        factory(User::class, 10)->create([
            'password' => Hash::make('password'),
        ])->each(function ($allUser) {
            $allUser->profile()->save(factory(UserProfile::class)->make());
            $allUser->preference()->save(factory(UserPreference::class)->make());
        });
    }
}
