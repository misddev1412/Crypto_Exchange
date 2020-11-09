<?php
return [

    'fixed_roles' => [USER_ROLE_ADMIN, USER_ROLE_USER],

    'path_profile_image' => 'images/users/',
    'path_image' => 'images/',
    'language_icon' => 'images/languages/',
    'ticket_attachment' => 'images/tickets/',
    'path_coin_icon' => 'images/coin-icons/',
    'path_cart_icon' => 'images/cart-icons/',
    'path_regular_site_image' => 'images/regular_site/',
    'path_dashboard_icon' => 'images/dashboard-icons/',
    'path_post_feature_image' => 'images/posts/',
    'path_deposit_receipt' => 'images/deposit/receipts/',
    'email_status' => [
        ACTIVE => ['color_class' => 'success'],
        INACTIVE => ['color_class' => 'danger'],
    ],
    'verification_status' => [
        VERIFIED => ['color_class' => 'success'],
        UNVERIFIED => ['color_class' => 'danger'],
    ],
    'active_status' => [
        ACTIVE => ['color_class' => 'success'],
        INACTIVE => ['color_class' => 'danger'],
    ],
    'account_status' => [
        STATUS_ACTIVE => ['color_class' => 'success'],
        STATUS_INACTIVE => ['color_class' => 'warning'],
        STATUS_DELETED => ['color_class' => 'danger'],
    ],
    'kyc_status' => [
        STATUS_REVIEWING => ['color_class' => 'warning'],
        STATUS_VERIFIED => ['color_class' => 'success'],
        STATUS_EXPIRED => ['color_class' => 'danger'],
        STATUS_DECLINED => ['color_class' => 'danger'],
    ],
    'financial_status' => [
        ACTIVE => ['color_class' => 'success'],
        INACTIVE => ['color_class' => 'danger'],
    ],
    'maintenance_accessible_status' => [
        ACTIVE => ['color_class' => 'success'],
        INACTIVE => ['color_class' => 'danger'],
    ],

    'ticket_status' => [
        STATUS_OPEN => ['color_class' => 'info'],
        STATUS_PROCESSING => ['color_class' => 'warning'],
        STATUS_RESOLVED => ['color_class' => 'success'],
        STATUS_CLOSED => ['color_class' => 'danger'],
    ],

    'transaction_status' => [
        STATUS_PENDING => ['color_class' => 'info'],
        STATUS_REVIEWING => ['color_class' => 'warning'],
        STATUS_PROCESSING => ['color_class' => 'warning'],
        STATUS_FAILED => ['color_class' => 'danger'],
        STATUS_CANCELED => ['color_class' => 'danger'],
        STATUS_COMPLETED => ['color_class' => 'success'],
        STATUS_EMAIL_SENT => ['color_class' => 'info'],
    ],

    'image_extensions' => ['png', 'jpg', 'jpeg', 'gif'],

    'strip_tags' => [
        'escape_text' => ['beginning_text', 'ending_text', 'company_name'],
        'escape_full_text' => ['editor_content'],
        'allowed_tag_for_escape_text' => '<p><br><b><i><u><strong><ul><ol><li>',
        'allowed_tag_for_escape_full_text' => '<h1><h2><h3><h4><h5><h6><hr><article><section><video><audio><table><tbody><tr><td><thead><tfoot><footer><header><p><br><b><i><u><strong><ul><ol><dl><dt><li><div><sub><sup><span><a><pre>',
    ],

    'available_commands' => [
        'cache' => 'cache:clear',
        'config' => 'config:clear',
        'route' => 'route:clear',
        'view' => 'view:clear',
    ],
    'currency_transferable' => [COIN_TYPE_FIAT, COIN_TYPE_CRYPTO],
    'currency_non_crypto' => [COIN_TYPE_FIAT],
];
