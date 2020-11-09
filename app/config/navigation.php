<?php
return [
    'registered_place' => [
        'top-nav',
        'profile-nav',
        'side-nav',
        'footer-nav-one',
        'footer-nav-two',
        'footer-nav-three',
    ],
    'navigation_template' =>[
        'default_nav' => [
            'navigation_wrapper_start'=> '<ul id="lf-main-nav" class="lf-main-nav d-flex justify-content-end">',
            'navigation_wrapper_end'=> '</ul>',
            'navigation_item_wrapper_start'=> '<li>',
            'navigation_item_wrapper_end'=> '</li>',
            'navigation_sub_menu_wrapper_start'=> '<ul>',
            'navigation_sub_menu_wrapper_end'=> '</ul>',
            'navigation_item_link_active_class'=> 'active',
        ],

        'profile_dropdown' => [
            'navigation_wrapper_start'=> '',
            'navigation_wrapper_end'=> '',
            'navigation_item_wrapper_start'=> '',
            'navigation_item_wrapper_end'=> '',
            'navigation_item_icon_wrapper_start'=> '<i>',
            'navigation_item_icon_wrapper_end'=> '</i>',
            'navigation_item_link_class'=> 'dropdown-item',
            'navigation_item_icon_position'=> 'text-left',
            'navigation_item_link_active_class'=> 'active',
            'navigation_item_active_class_on_anchor_tag'=> false,
        ],
        'side_nav' => [
            'navigation_wrapper_start'=> '<ul>',
            'navigation_wrapper_end'=> '</ul>',
            'navigation_item_wrapper_start'=> '<li>',
            'navigation_item_wrapper_end'=> '</li>',
            'navigation_item_link_active_class'=> 'active',
        ],
        'footer_nav' => [
            'navigation_wrapper_start'=> '<ul class="footer-widget-menu">',
            'navigation_wrapper_end'=> '</ul>',
            'navigation_item_wrapper_start'=> '<li>',
            'navigation_item_wrapper_end'=> '</li>',
            'navigation_sub_menu_wrapper_start'=> '',
            'navigation_sub_menu_wrapper_end'=> '',
            'navigation_item_link_active_class'=> 'active',
        ],
    ],
];
