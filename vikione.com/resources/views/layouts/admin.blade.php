<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="js">
<head>
    <meta charset="utf-8">
    <meta name="apps" content="{{ site_whitelabel('apps') }}">
    <meta name="author" content="{{ site_whitelabel('author') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="site-token" content="{{ site_token() }}">
    <link rel="shortcut icon" href="{{ site_favicon() }}">
    <title>@yield('title') | {{ site_whitelabel('title') }}</title>
    <link rel="stylesheet" href="{{ asset(style_theme('vendor')) }}">
    <link rel="stylesheet" href="{{ asset(style_theme('admin')) }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
    @stack('header')
</head>

<body class="admin-dashboard page-user">
    <div class="topbar-wrap">
        <div class="topbar is-sticky">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <ul class="topbar-nav d-lg-none">
                        <li class="topbar-nav-item relative">
                            <a class="toggle-nav" href="#">
                                <div class="toggle-icon">
                                    <span class="toggle-line"></span>
                                    <span class="toggle-line"></span>
                                    <span class="toggle-line"></span>
                                    <span class="toggle-line"></span>
                                </div>
                            </a>
                        </li>{{-- .topbar-nav-item --}}
                    </ul>{{-- .topbar-nav --}}
                    <div class="topbar-logo">
                        <a href="{{ url('/')}}" class="site-brand">
                            
                                <img height="40" src="{{ site_whitelabel('logo-light') }}" srcset="{{ site_whitelabel('logo-light2x') }}" alt="{{ site_whitelabel('name') }}">
                            
                        </a>
                    </div>
                    <ul class="topbar-nav">
                        <li class="topbar-nav-item relative">
                            <span class="user-welcome d-none d-lg-inline-block">Hello! {{ ucfirst(auth()->user()->role) }}</span>
                            <a class="toggle-tigger user-thumb" href="#"><em class="ti ti-user"></em></a>
                            <div class="toggle-class dropdown-content dropdown-content-right dropdown-arrow-right user-dropdown">
                                <div class="user-status">
                                    <h6 class="user-status-title">{{ auth()->user()->name }} <span class="text-white-50">({{ set_id(auth()->user()->id) }})</span></h6>
                                    <div class="user-status-balance"><small>{{ auth()->user()->email }}</small></div>
                                </div>
                                <ul class="user-links">
                                    <li><a href="{{ route('admin.profile') }}"><i class="ti ti-id-badge"></i>My Profile</a></li>

                                    <li><a href="{{ route('admin.profile.activity') }}"><i class="ti ti-eye"></i>Activity</a></li>
                                </ul>
                                <ul class="user-links bg-light">
                                    <li><a href="{{ route('log-out') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="ti ti-power-off"></i>Logout</a></li>
                                </ul>
                            </div>
                        </li>{{-- .topbar-nav-item --}}
                    </ul>{{-- .topbar-nav --}}
                </div>
            </div>{{-- .container --}}
        </div>{{-- .topbar --}}
        <div class="navbar">
            <div class="container">
                <div class="navbar-innr">
                    <ul class="navbar-menu" id="main-nav">
                        <li><a href="{{ route('admin.home') }}"><em class="ikon ikon-dashboard"></em> Dashboard</a></li>
                        @if(gup('tranx')||gup('view_tranx'))
                        <li{!! ((is_page('transactions')||is_page('transactions.pending')||is_page('transactions.approved')||is_page('transactions.bonuses'))? ' class="active"' : '') !!}>
                            <a href="{{ route('admin.transactions', 'pending') }}"><em class="ikon ikon-transactions"></em> Transactions</a>
                        </li>
                        @endif
                        @if(nio_module()->has('Withdraw') && has_route('withdraw:admin.index') && gup('withdraw'))
                        <li{!! ((is_page('withdraw'))? ' class="active"' : '') !!}>
                            <a href="{{ route('withdraw:admin.index') }}"><em class="ikon ikon-wallet"></em> Withdraw</a>
                        </li>
                        @endif
                        @if(gup('kyc')||gup('view_kyc'))
                        <li{!! ((is_page('kyc-list')||is_page('kyc-list.pending')||is_page('kyc-list.approved')||is_page('kyc-list.missing'))? ' class="active"' : '') !!}>
                            <a href="{{ route('admin.kycs', 'pending') }}"><em class="ikon ikon-docs"></em> KYC List</a>
                        </li>
                        @endif
                        @if(gup('user')||gup('view_user'))
                        <li{!! ((is_page('users')||is_page('users.user')||is_page('users.admin'))? ' class="active"' : '') !!}>
                            <a href="{{ route('admin.users', 'user') }}"><em class="ikon ikon-user-list"></em> Users List</a>
                        </li>
                        @endif
                        @if(gup('stage'))
                        <li{!! ((is_page('stages'))? ' class="active"' : '') !!}>
                            <a href="{{ route('admin.stages') }}"><em class="ikon ikon-coins"></em> ICO/STO Stage</a>
                        </li>
                        @endif
                        @if(gup('setting'))
                        <li class="has-dropdown"><a class="drop-toggle" href="javascript:void(0)"><em class="ikon ikon-settings"></em> Settings</a>
                            <ul class="navbar-dropdown">
                                <li><a href="{{ route('admin.stages.settings') }}">ICO/STO Setting</a></li>
                                <li><a href="{{ route('admin.settings') }}">Website Setting</a></li>
                                <li><a href="{{ route('admin.settings.referral') }}">Referral Setting</a></li>
                                <li><a href="{{ route('admin.settings.point') }}">Point Setting</a></li>
                                <li><a href="{{ route('admin.settings.affiliate') }}">Affiliate Setting</a></li>
                                <li><a href="{{ route('admin.settings.email') }}">Mailing Setting</a></li>
                                <li><a href="{{ route('admin.payments.setup') }}">Payment Methods</a></li>
                                <li><a href="{{ route('admin.pages') }}">Manage Pages</a></li>
                                <li><a href="{{ route('admin.settings.api') }}">Application API</a></li>
                                <li><a href="{{ route('admin.lang.manage') }}">Manage Languages</a></li>
                                <li><a href="{{ route('admin.system') }}">System Status</a></li>
                                @if(has_route('manage_access:admin.index'))
                                <li><a href="{{ route('manage_access:admin.index') }}">Manage Admin</a></li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        <li class="has-dropdown {!! ((is_page('sellgoods/*'))? 'active' : '') !!}{!! ((is_page('buysell/*'))? 'active' : '') !!}">
                            <a class="drop-toggle" href="javascript:void(0)"><em class="ikon ikon-settings"></em> Others</a>
                            <ul class="navbar-dropdown">
                                <li><a href="{{ route('admin.buysell') }}">Buy / Sell</a></li>
                                <li><a href="{{ route('admin.sellgoods') }}">Sell goodss</a></li>
                            </ul>
                        </li>                          
                    </ul>
                    @if(is_super_admin())
                    <ul class="navbar-btns">
						<li><a style="font-size: 13px;background: #ffcd32;color: #000;border: none;font-weight: 700;" id="update-token" class="btn btn-auto btn-xs btn-danger" href="{{ route('admin.token.one.update') }}"><em class="ti ti-exchange-vertical"></em><span>PROFIT</span></a></li>

                        <li><a id="clear-cache" class="btn btn-auto btn-xs btn-dark btn-outline" href="{{ route('admin.clear.cache') }}"><em class="ti ti-trash"></em><span>CLEAR CACHE</span></a></li>
                        <!-- <li><a id="interet-point" class="btn btn-auto btn-xs btn-warning text-white"><em class="ti ti-plus"></em><span>Interest Point</span></a></li> -->
                    </ul>
                    @endif
                </div>{{-- .navbar-innr --}}
            </div>{{-- .container --}}
        </div>{{-- .navbar --}}
    </div>{{-- .topbar-wrap --}}
    
    @yield('content')

    <div class="footer-bar">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-12">
                    <div class="copyright-text text-center pb-3">{!! site_whitelabel('copyright') !!}</div>
                </div>
            </div>
        </div>{{-- .container --}}
    </div>{{-- .footer-bar --}}
    <form id="logout-form" action="{{ (is_maintenance() ? route('admin.logout') : route('logout')) }}" method="POST" style="display: none;">
        @csrf
    </form>
    <div id="ajax-modal"></div>
    @yield('modals')
    <div class="page-overlay">
        <div class="spinner"><span class="sp sp1"></span><span class="sp sp2"></span><span class="sp sp3"></span></div>
    </div>

@if(gws('theme_custom'))
    <link rel="stylesheet" href="{{ asset(style_theme('custom')) }}">
@endif
@php
    $admin_routes = '';
    $route_urls = [
        'get_trnx_url' => 'admin.ajax.transactions.view',
        'view_user_url' => 'admin.ajax.users.view',
        'show_user_info' => 'admin.ajax.users.show',
        'pm_manage_url' => 'admin.ajax.payments.view',
        'get_kyc_url' => 'admin.ajax.kyc.ajax_show',
        'update_kyc_url' => 'admin.ajax.kyc.update',
        'trnx_action_url' => 'admin.ajax.transactions.update',
        'trnx_adjust_url' => 'admin.ajax.transactions.adjustement',
        'get_et_url' => 'admin.ajax.settings.email.template.view',
        'clear_cache_url' => 'admin.clear.cache',
        'whitepaper_uploads' => 'admin.ajax.pages.upload',
        'view_page_url' => 'admin.ajax.pages.view',
        'unverified_delete_url' => 'admin.ajax.users.delete',
        'stage_action_url' => 'admin.ajax.stages.actions',
        'stage_active_url' => 'admin.ajax.stages.active',
        'stage_pause_url' => 'admin.ajax.stages.pause',
        'quick_update_url' => 'admin.ajax.payments.qupdate',
        'transfer_action_url' => 'transfer:admin.update',
        'meta_update_url' => 'admin.ajax.settings.meta.update'
    ];
    foreach($route_urls as $var => $route) {
        $admin_routes .= (has_route($route)) ? $var.' = "'.route($route).'", ' : '';
    }
@endphp
    <script type="text/javascript">
        var base_url = "{{ url('/') }}", {!! $admin_routes !!} csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); 
    </script>
    <script src="{{ asset('assets/js/jquery.bundle.js').css_js_ver() }}"></script>
    <script src="{{ asset('assets/js/script.js').css_js_ver() }}"></script>
    <script src="{{ asset('assets/js/admin.app.js').css_js_ver() }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.all.min.js').css_js_ver() }}"></script>
    @yield('script')
    @stack('footer')
    @if(session()->has('global'))
    <script type="text/javascript">
        show_toast("info","{{ session('global') }}");
    </script>
    @endif
</body>
</html>
