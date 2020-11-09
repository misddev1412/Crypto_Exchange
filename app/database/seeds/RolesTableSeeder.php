<?php

use App\Models\Core\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $adminSection = [
         "user_managements" => [
             "reader_access",
             "creation_access",
             "modifier_access",
             "deletion_access"
         ],
         "notice_managements" => [
             "reader_access",
             "creation_access",
             "modifier_access",
             "deletion_access"
         ],
         "language_managements" => [
             "reader_access",
             "creation_access",
             "modifier_access",
             "deletion_access"
         ],
         "ticket_management" => [
             "reader_access",
             "modifier_access",
             "commenting_access"
         ],
         "kyc_management" => [
             "reader_access",
             "modifier_access"
         ],
         "system_bank_management" => [
             "reader_access",
             "creation_access",
             "modifier_access"
         ],
         "post_category_management" => [
             "reader_access",
             "creation_access",
             "modifier_access"
         ],
         "post_management" => [
             "reader_access",
             "creation_access",
             "modifier_access",
             "deletion_access"
         ],
         "page_management" => [
             "reader_access",
             "creation_access",
             "modifier_access",
             "deletion_access"
         ],
         "review_deposit_management" => [
             "reader_access",
             "modifier_access",
             "deletion_access"
         ],
         "review_withdrawal_management" => [
             "reader_access",
             "modifier_access"
         ],
         "wallet_management" => [
             "reader_access"
         ]
     ];

        $userSection = [
            "tickets" => [
                "reader_access",
                "creation_access",
                "closing_access",
                "commenting_access"
            ],
            "wallets" => [
                "reader_access",
                "deposit_access",
                "withdrawal_access"
            ],
            "back_accounts" => [
                "reader_access",
                "creation_access",
                "modifier_access",
                "deletion_access"
            ],
            "post_comments" => [
                "creation_access"
            ],
            "order" => [
                "reader_access",
                "creation_access",
                "deletion_access"
            ],
            "trading" => [
                "reader_access"
            ],
            "referral" => [
                "reader_access",
                "creation_access"
            ],
            "user_activity" => [
                "reader_access"
            ],
            "transactions" => [
                "reader_access"
            ],
            "exchange" => [
                "reader_access"
            ],
        ];

        $adminPermissions = [
            "admin_section" => $adminSection,
            "user_section" => $userSection
        ];

        factory(Role::class)->create([
            'name' => 'Admin',
            'permissions' => $adminPermissions,
            'accessible_routes' => build_permission($adminPermissions, 'admin'),
            'is_active' => ACTIVE
        ]);

        $userPermissions = [
            "user_section" => $userSection
        ];

        factory(Role::class)->create([
            'name' => 'User',
            'permissions' => $userPermissions,
            'accessible_routes' => build_permission($userPermissions, 'user'),
            'is_active' => ACTIVE
        ]);
    }
}
