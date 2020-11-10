<?php

return [
    'configurable_routes' => [
        'admin_section' => [
            'application-settings' => [
                ROUTE_GROUP_MODIFIER_ACCESS => [
                    'application-settings.index',
                    'application-settings.edit',
                    'application-settings.update',
                ],
            ],
            'role_managements' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'roles.index',
                ],
                ROUTE_GROUP_CREATION_ACCESS => [
                    'roles.create',
                    'roles.store',
                ],
                ROUTE_GROUP_MODIFIER_ACCESS => [
                    'roles.edit',
                    'roles.update',
                    'roles.status',
                ],
                ROUTE_GROUP_DELETION_ACCESS => [
                    'roles.destroy',
                ],
            ],
            'user_managements' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'admin.users.index',
                    'admin.users.show',
                    'user.trading-history',
                    'user.open.order',
                    'user.wallet.deposit-history',
                    'user.withdrawal-history',
                    'users.activities',
                ],
                ROUTE_GROUP_CREATION_ACCESS => [
                    'admin.users.create',
                    'admin.users.store',
                    'user.wallets.adjust-amount.create',
                    'user.wallets.adjust-amount.store',
                ],
                ROUTE_GROUP_MODIFIER_ACCESS => [
                    'admin.users.edit',
                    'admin.users.update',
                ],
                ROUTE_GROUP_DELETION_ACCESS => [
                    'admin.users.update.status',
                    'admin.users.edit.status',
                ],
            ],
            'notice_managements' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'notices.index',
                    'notices.show'
                ],
                ROUTE_GROUP_CREATION_ACCESS => [
                    'notices.create',
                    'notices.store'
                ],
                ROUTE_GROUP_MODIFIER_ACCESS => [
                    'notices.edit',
                    'notices.update',
                ],
                ROUTE_GROUP_DELETION_ACCESS => [
                    'notices.destroy',
                ]
            ],
            'menu_manager' => [
                ROUTE_GROUP_FULL_ACCESS => [
                    'menu-manager.index',
                    'menu-manager.save',
                ],
            ],
            'log_viewer' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'logs.index'
                ]
            ],
            'language_managements' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'languages.index',
                    'languages.settings',
                    'languages.translations'
                ],
                ROUTE_GROUP_CREATION_ACCESS => [
                    'languages.create',
                    'languages.store'
                ],
                ROUTE_GROUP_MODIFIER_ACCESS => [
                    'languages.edit',
                    'languages.update',
                    'languages.update.settings',
                    'languages.sync',
                ],
                ROUTE_GROUP_DELETION_ACCESS => [
                    'languages.destroy'
                ]
            ],
            'ticket_management' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'admin.tickets.index',
                    'admin.tickets.show',
                    'admin.tickets.attachment.download'
                ],
                ROUTE_GROUP_MODIFIER_ACCESS => [
                    'admin.tickets.close',
                    'admin.tickets.resolve',
                    'admin.tickets.assign',
                ],
                'commenting_access' => [
                    'admin.tickets.comment.store',
                ]
            ],
            'kyc_management' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'kyc-management.index',
                    'kyc-management.show',
                ],
                ROUTE_GROUP_MODIFIER_ACCESS => [
                    'kyc-management.decline',
                    'kyc-management.expired',
                ],
            ],
            'system_bank_management' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'system-banks.index'
                ],
                ROUTE_GROUP_CREATION_ACCESS => [
                    'system-banks.create',
                    'system-banks.store',
                ],
                ROUTE_GROUP_MODIFIER_ACCESS => [
                    'system-banks.toggle-status',
                ],
            ],
            'coin_management' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'coins.index',
                ],
                ROUTE_GROUP_CREATION_ACCESS => [
                    'coins.create',
                    'coins.store',
                ],
                ROUTE_GROUP_MODIFIER_ACCESS => [
                    'coins.edit',
                    'coins.update',
                    'coins.withdrawal.edit',
                    'coins.withdrawal.update',
                    'coins.exchange.edit',
                    'coins.deposit.update',
                    'coins.api.edit',
                    'coins.api.update',
                    'coins.icon.update',
                    'coins.toggle-status',
                    'coins.reset-addresses',
                ]
            ],
            'coin_pair_management' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'coin-pairs.index',
                    'coin-pairs.show',
                ],
                ROUTE_GROUP_CREATION_ACCESS => [
                    'coin-pairs.create',
                    'coin-pairs.store',
                ],
                ROUTE_GROUP_MODIFIER_ACCESS => [
                    'coin-pairs.edit',
                    'coin-pairs.update',
                    'coin-pairs.make-status-default',
                    'coin-pairs.toggle-status'
                ],
                ROUTE_GROUP_DELETION_ACCESS => [
                    'coin-pairs.destroy',
                ]
            ],
            'post_category_management' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'post-categories.index'
                ],
                ROUTE_GROUP_CREATION_ACCESS => [
                    'post-categories.create',
                    'post-categories.store',
                ],
                ROUTE_GROUP_MODIFIER_ACCESS => [
                    'post-categories.edit',
                    'post-categories.update',
                    'post-categories.toggle-status',
                ]
            ],
            'post_management' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'posts.index',
                    'posts.show',
                ],
                ROUTE_GROUP_CREATION_ACCESS => [
                    'posts.create',
                    'posts.store',
                ],
                ROUTE_GROUP_MODIFIER_ACCESS => [
                    'posts.edit',
                    'posts.update',
                    'posts.toggle-status',
                ],
                ROUTE_GROUP_DELETION_ACCESS => [
                    'posts.delete'
                ]
            ],
            'page_management' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'pages.index'
                ],
                ROUTE_GROUP_CREATION_ACCESS => [
                    'pages.create',
                    'pages.store',
                ],
                ROUTE_GROUP_MODIFIER_ACCESS => [
                    'pages.edit',
                    'pages.update',
                    'pages.toggle-status',
                ],
                ROUTE_GROUP_DELETION_ACCESS => [
                    'pages.destroy'
                ]
            ],
            'review_deposit_management' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'admin.review.bank-deposits.index',
                    'admin.review.bank-deposits.show',
                ],
                ROUTE_GROUP_MODIFIER_ACCESS => [
                    'admin.adjust.bank-deposits',
                    'admin.review.bank-deposits.update',
                ],
                ROUTE_GROUP_DELETION_ACCESS => [
                    'admin.review.bank-deposits.destroy',
                ]
            ],
            'review_withdrawal_management' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'admin.review.withdrawals',
                    'admin.review.withdrawals.show',
                ],
                ROUTE_GROUP_MODIFIER_ACCESS => [
                    'admin.review.withdrawals.update',
                    'admin.review.withdrawals.update',
                    'admin.review.withdrawals.destroy',
                ]
            ],
            'wallet_management' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'wallet-managements.index'
                ],
            ],
        ],
        'user_section' => [
            'tickets' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'tickets.index',
                    'tickets.show',
                    'tickets.attachment.download'
                ],
                ROUTE_GROUP_CREATION_ACCESS => [
                    'tickets.create',
                    'tickets.store',
                ],
                'closing_access' => [
                    'tickets.close',
                ],
                'commenting_access' => [
                    'tickets.comment.store',
                ]
            ],
            'wallets' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'user.wallets.index',
                ],
                'deposit_access' => [
                    'user.wallets.deposits.index',
                    'user.wallets.deposits.show',
                    'user.wallets.deposits.update',
                    'user.wallets.deposits.destroy',
                    'user.wallets.deposits.create',
                    'user.wallets.deposits.store',
                ],
                'withdrawal_access' => [
                    'user.wallets.withdrawals.index',
                    'user.wallets.withdrawals.show',
                    'user.wallets.withdrawals.create',
                    'user.wallets.withdrawals.store',
                ],
            ],
            'back_accounts' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'bank-accounts.index'
                ],
                ROUTE_GROUP_CREATION_ACCESS => [
                    'bank-accounts.create',
                    'bank-accounts.store',
                ],
                ROUTE_GROUP_MODIFIER_ACCESS => [
                    'bank-accounts.edit',
                    'bank-accounts.update',
                    'bank-accounts.toggle-status',
                ],
                ROUTE_GROUP_DELETION_ACCESS => [
                    'bank-accounts.destroy',
                ],
            ],
            'post_comments' => [
                ROUTE_GROUP_CREATION_ACCESS => [
                    'posts.comment',
                    'posts.comment.reply',
                ]
            ],
            'order' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'user.open.order',
                    'order.index'
                ],
                ROUTE_GROUP_CREATION_ACCESS => [
                    'user.order.store',
                ],
                ROUTE_GROUP_DELETION_ACCESS => [
                    'user.order.destroy',
                ]
            ],
            'trading' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'my-trade-history'
                ],
            ],
            'referral' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'referral.users',
                    'referral.users.earnings',
                    'referral.earnings'
                ],
                ROUTE_GROUP_CREATION_ACCESS => [
                    'referral.link.show',
                ],
            ],
            'user_activity' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'my-activities.index'
                ]
            ],
            'transactions' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'my.recent-transactions'
                ]
            ],
            'exchange' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'exchange.get-my-open-orders',
                    'exchange.get-my-trades',
                    'exchange.get-wallet-summary'
                ]
            ],
            'personal_access_token' => [
                ROUTE_GROUP_READER_ACCESS => [
                    'personal-access-tokens.index',
                ],
                ROUTE_GROUP_CREATION_ACCESS => [
                    'personal-access-tokens.create',
                    'personal-access-tokens.store',
                ],
                ROUTE_GROUP_DELETION_ACCESS => [
                    'personal-access-tokens.destroy',
                ],
            ],
        ],
    ],
    'role_based_routes' => [
        USER_ROLE_USER => [
            'user.test'
        ]
    ],

    ROUTE_TYPE_AVOIDABLE_MAINTENANCE => [
        'login',
    ],

    ROUTE_TYPE_AVOIDABLE_UNVERIFIED => [
        'logout',
        'profile.index',
        'notifications.index',
        'notifications.mark-as-read'
    ],
    ROUTE_TYPE_AVOIDABLE_INACTIVE => [
        'logout',
        'profile.index',
        'notifications.index',
        'notifications.mark-as-read',
        'notifications.mark-as-unread',
    ],
    ROUTE_TYPE_FINANCIAL => [

    ],

    ROUTE_TYPE_GLOBAL => [
        'logout',
        'profile.index',
        'profile.edit',
        'profile.update',
        'preference.index',
        'preference.edit',
        'preference.update',
        'profile.change-password',
        'profile.update-password',
        'profile.avatar.update',
        'profile.google-2fa.create',
        'profile.google-2fa.store',
        'profile.google-2fa.destroy',
        'profile.google-2fa.verify',
        'notifications.index',
        'notifications.mark-as-read',
        'notifications.mark-as-unread',
        'kyc-verifications.index',
        'kyc-verifications.store',
    ],

];
