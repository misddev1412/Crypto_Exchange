<?php

if (!function_exists('no_header_layout')) {
    function no_header_layout($input = null)
    {
        $output = [
            0 => __('Dark'),
            1 => __('Light'),
        ];
        return is_null($input) ? $output : $output[$input];
    }
}
if (!function_exists('top_nav_type')) {
    function top_nav_type($input = null)
    {
        $output = [
            0 => __('Dark'),
            1 => __('Light'),
        ];
        return is_null($input) ? $output : $output[$input];
    }
}
if (!function_exists('side_nav_type')) {
    function side_nav_type($input = null)
    {
        $output = [
            0 => __('Solid'),
            1 => __('Transparent'),
        ];
        return is_null($input) ? $output : $output[$input];
    }
}
if (!function_exists('navigation_type')) {
    function navigation_type($input = null)
    {
        $output = [
            0 => __('Top navigation'),
            1 => __('Side navigation'),
            2 => __('Both'),
        ];
        return is_null($input) ? $output : $output[$input];
    }
}
if (!function_exists('inversed_logo')) {
    function inversed_logo($input = null)
    {
        $output = [
            ACTIVE => __('Enabled'),
            INACTIVE => __('Disabled')
        ];
        return is_null($input) ? $output : $output[$input];
    }
}
if (!function_exists('maintenance_status')) {
    function maintenance_status($input = null)
    {
        $output = [
            ACTIVE => __('Enabled'),
            INACTIVE => __('Disabled')
        ];
        return is_null($input) ? $output : $output[$input];
    }
}

if (!function_exists('verified_status')) {
    function verified_status($input = null)
    {
        $output = [
            ACTIVE => __('Verified'),
            INACTIVE => __('Unverified')
        ];

        return is_null($input) ? $output : $output[$input];
    }
}

if (!function_exists('enable_status')) {
    function enable_status($input = null)
    {
        $output = [
            ENABLE => __('Enable'),
            DISABLE => __('Disable')
        ];

        return is_null($input) ? $output : $output[$input] . 'd';
    }
}

if (!function_exists('transaction_status')) {
    function transaction_status($input = null)
    {
        $output = [
            STATUS_PENDING => __('Pending'),
            STATUS_REVIEWING => __('Reviewing'),
            STATUS_PROCESSING => __('Processing'),
            STATUS_COMPLETED => __('Completed'),
            STATUS_CANCELING => __('Canceling'),
            STATUS_CANCELED => __('Canceled'),
            STATUS_FAILED => __('Failed'),
            STATUS_EMAIL_SENT => __('Email Sent'),
        ];

        return is_null($input) ? $output : $output[$input];
    }
}

if (!function_exists('financial_status')) {
    function financial_status($input = null)
    {
        $output = [
            ACTIVE => __('Active'),
            INACTIVE => __('Inactive')
        ];

        return is_null($input) ? $output : $output[$input];
    }
}

if (!function_exists('maintenance_accessible_status')) {
    function maintenance_accessible_status($input = null)
    {
        $output = [
            ACTIVE => __('Enable'),
            INACTIVE => __('Disable')
        ];

        return is_null($input) ? $output : $output[$input];
    }
}

if (!function_exists('account_status')) {
    function account_status($input = null)
    {
        $output = [
            STATUS_ACTIVE => __('Active'),
            STATUS_INACTIVE => __('Suspended'),
            STATUS_DELETED => __('Deleted')
        ];

        return is_null($input) ? $output : $output[$input];
    }
}

if (!function_exists('active_status')) {
    function active_status($input = null)
    {
        $output = [
            ACTIVE => __('Active'),
            INACTIVE => __('Inactive'),
        ];

        return is_null($input) ? $output : $output[$input];
    }
}

if (!function_exists('publish_status')) {
    function publish_status($input = null)
    {
        $output = [
            ACTIVE => __('Published'),
            INACTIVE => __('Unpublished'),
        ];

        return is_null($input) ? $output : $output[$input];
    }
}

if (!function_exists('api_permission')) {
    function api_permission($input = null)
    {
        $output = [
            ROUTE_REDIRECT_TO_UNAUTHORIZED => '401',
            ROUTE_REDIRECT_TO_UNDER_MAINTENANCE => 'under_maintenance',
            ROUTE_REDIRECT_TO_EMAIL_UNVERIFIED => 'email_unverified',
            ROUTE_REDIRECT_TO_ACCOUNT_SUSPENDED => 'account_suspension',
            ROUTE_REDIRECT_TO_FINANCIAL_ACCOUNT_SUSPENDED => 'financial_suspension',
            REDIRECT_ROUTE_TO_USER_AFTER_LOGIN => 'login_success',
            REDIRECT_ROUTE_TO_LOGIN => 'login',
        ];

        return is_null($input) ? $output : $output[$input];
    }
}

if (!function_exists('language_switcher_items')) {
    function language_switcher_items($input = null)
    {
        $output = [
            'name' => __('Name'),
            'short_code' => __('Short Code'),
            'icon' => __('Icon')
        ];
        return is_null($input) ? $output : $output[$input];
    }
}

if (!function_exists('notices_types')) {
    function notices_types($input = null)
    {
        $output = [
            'warning' => __('Warning'),
            'danger' => __('Critical'),
            'info' => __('Info')
        ];
        return is_null($input) ? $output : $output[$input];
    }
}
if (!function_exists('notices_visible_types')) {
    function notices_visible_types($input = null)
    {
        $output = [
            NOTICE_VISIBLE_TYPE_PUBLIC => __('Public'),
            NOTICE_VISIBLE_TYPE_PRIVATE => __('Logged In User Only'),
        ];
        return is_null($input) ? $output : $output[$input];
    }
}

if (!function_exists('ticket_status')) {
    function ticket_status($input = null)
    {
        $output = [
            STATUS_OPEN => __('Open'),
            STATUS_PROCESSING => __('Progressing'),
            STATUS_RESOLVED => __('Resolved'),
            STATUS_CLOSED => __('Closed'),
        ];

        return is_null($input) ? $output : $output[$input];
    }
}

if (!function_exists('order_status')) {
    function order_status($input = null)
    {
        $output = [
            STATUS_PENDING => __('Pending'),
            STATUS_COMPLETED => __('Completed'),
            STATUS_CANCELED => __('Canceled'),
        ];

        return is_null($input) ? $output : $output[$input];
    }
}


if (!function_exists('datatable_downloadable_type')) {
    function datatable_downloadable_type($input = null)
    {
        $output = [
            'dompdf' => [
                'extension' => 'pdf',
                'label' => __('Download as PDF'),
                'icon_class' => 'fa fa-file-pdf-o text-danger'
            ],
            'csv' => [
                'extension' => 'csv',
                'label' => __('Download as CSV'),
                'icon_class' => 'fa fa-file-excel-o text-success'
            ]
        ];
        return is_null($input) ? $output : $output[$input];
    }
}

if (!function_exists('kyc_status')) {
    function kyc_status($input = null)
    {
        $output = [
            STATUS_REVIEWING => __('Reviewing'),
            STATUS_VERIFIED => __('Verified'),
            STATUS_DECLINED => __('Decline'),
            STATUS_EXPIRED => __('Expired'),
        ];

        return is_null($input) ? $output : $output[$input];
    }
}

if (!function_exists('id_type')) {
    function kyc_type($input = null)
    {
        $output = [
            KYC_TYPE_PASSPORT => __('Passport'),
            KYC_TYPE_NID => __('NID'),
            KYC_TYPE_DRIVING_LICENSE => __('Driver License'),
        ];

        return is_null($input) ? $output : $output[$input];
    }

    if (!function_exists('verification_status')) {
        function verification_status($input = null)
        {
            $output = [
                VERIFIED => __('Verified'),
                UNVERIFIED => __('Unverified')
            ];

            return is_null($input) ? $output : $output[$input];
        }
    }
    if (!function_exists('coin_types')) {
        function coin_types($input = null)
        {
            $output = [
                COIN_TYPE_FIAT => __('Fiat'),
                COIN_TYPE_CRYPTO => __('Crypto'),
            ];

            return is_null($input) ? $output : $output[$input];
        }
    }

    if (!function_exists('crypto_apis')) {
        function crypto_apis($input = null)
        {
            $output = [
                API_COINPAYMENT => __('Coinpayments API'),
                API_BITCOIN => __('BTC Forked API'),
            ];

            return is_null($input) ? $output : $output[$input];
        }
    }

    if (!function_exists('fiat_apis')) {
        function fiat_apis($input = null)
        {
            $output = [
                API_BANK => __('Bank'),
            ];

            return is_null($input) ? $output : $output[$input];
        }
    }

    if (!function_exists('coin_apis')) {
        function coin_apis($input = null)
        {
            $output = crypto_apis() + fiat_apis();

            return is_null($input) ? $output : $output[$input];
        }
    }


    if (!function_exists('get_bitcoin_fields')) {
        function get_bitcoin_fields()
        {
            return [
                '_api_scheme' => [
                    'field_type' => 'select',
                    'field_label' => "Schema",
                    'type_function' => true,
                    'field_value' => 'http_schemes',
                ],
                '_api_host' => [
                    'field_type' => 'text',
                    'validation' => 'required',
                    'field_label' => "Host",
                    'encryption' => true
                ],
                '_api_port' => [
                    'field_type' => 'text',
                    'validation' => 'required',
                    'field_label' => "Port",
                    'encryption' => true
                ],
                '_api_rpc_user' => [
                    'field_type' => 'text',
                    'validation' => 'required',
                    'field_label' => "RPC Username",
                    'encryption' => true
                ],
                '_api_rpc_password' => [
                    'field_type' => 'text',
                    'validation' => 'required',
                    'field_label' => "RPC Password",
                    'encryption' => true
                ],
                '_api_ssl_cert' => [
                    'field_type' => 'text',
                    'field_label' => "SSL Cert File Location",
                ]
            ];
        }
    }

    if (!function_exists('order_type')) {
        function order_type($input = null)
        {
            $output = [
                ORDER_TYPE_BUY => __('Buy'),
                ORDER_TYPE_SELL => __('Sell'),
            ];

            return is_null($input) ? $output : $output[$input];
        }
    }

    if (!function_exists('order_categories')) {
        function order_categories($input = null)
        {
            $output = [
                ORDER_CATEGORY_LIMIT => __('Limit'),
                ORDER_CATEGORY_MARKET => __('Market'),
                ORDER_CATEGORY_STOP_LIMIT => __('Stop Limit'),
            ];

            return is_null($input) ? $output : $output[$input];
        }
    }
}


if (!function_exists('chart_data_interval')) {
    function chart_data_interval()
    {
        return $intervals = [5 => '5min', 15 => '15min', 30 => '30min', 120 => '2hr', 240 => '4hr', 1440 => '1day'];
    }
}

if (!function_exists('fee_types')) {
    function fee_types($input = null)
    {
        $output = [
            FEE_TYPE_FIXED => __("Fixed"),
            FEE_TYPE_PERCENT => __("Percent"),
        ];

        return is_null($input) ? $output : $output[$input];
    }
}

if (!function_exists('http_schemes')) {
    function http_schemes($input = null)
    {
        $output = [
            'http' => __("HTTP"),
            'https' => __("HTTPS"),
        ];

        return is_null($input) ? $output : $output[$input];
    }
}
if (!function_exists('transaction_type')) {
    function transaction_type($input = null)
    {
        $output = [
            TRANSACTION_TYPE_BALANCE_INCREMENT => __('Increment'),
            TRANSACTION_TYPE_BALANCE_DECREMENT => __('Decrement'),
        ];

        return is_null($input) ? $output : $output[$input];
    }
}
