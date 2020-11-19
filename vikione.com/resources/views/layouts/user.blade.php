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
    <link rel="stylesheet" href="{{ asset(style_theme('user')) }}">
    <link rel="stylesheet" href="{{ asset(style_theme('custom')) }}">
    <!-- <link rel="stylesheet" href="{{ asset('assets/plugins/swal/sweetalert2.min.css') }}"> -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
    @stack('header')
@if(get_setting('site_header_code', false))
    {{ html_string(get_setting('site_header_code')) }}
@endif
</head>
<body class="user-dashboard page-user theme-modern">
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

                    <a class="topbar-logo" href="{{ url('/') }}">
                        <img height="40" src="{{ site_whitelabel('logo-light') }}" srcset="{{ site_whitelabel('logo-light2x') }}" alt="{{ site_whitelabel('name') }}">
                    </a>
                    <ul class="topbar-nav">
                        <li class="topbar-nav-item relative">
                            <span class="user-welcome d-none d-lg-inline-block">{{__('Welcome!')}} {{ auth()->user()->name }}</span>
                            <a class="toggle-tigger user-thumb" href="#"><em class="ti ti-user"></em></a>
                            <div class="toggle-class dropdown-content dropdown-content-right dropdown-arrow-right user-dropdown">
                                {!! UserPanel::user_balance() !!}
                                {!! UserPanel::user_menu_links() !!}
                                {!! UserPanel::user_logout_link() !!}
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
                        <li><a href="{{ route('user.home') }}"><em class="ikon ikon-dashboard"></em> {{__('Dashboard')}}</a></li>
                        <li><a href="{{ route('user.token') }}"><em class="ikon ikon-coins"></em> {{__('Buy Token')}}</a></li>
                        @if(get_page('distribution', 'status') == 'active')
                        <li><a href="{{ route('public.pages', 'distribution') }}"><em class="ikon ikon-distribution"></em> {{ get_page('distribution', 'title') }}</a></li>
                        @endif
                        <li><a href="{{ route('user.transactions') }}"><em class="ikon ikon-transactions"></em> {{__('Transactions')}}</a></li>
                        @if(nio_module()->has('Withdraw') && has_route('withdraw:user.index'))
                        <li{!! ((is_page('withdraw'))? ' class="active"' : '') !!}>
                            <a href="{{ route('withdraw:user.index') }}"><em class="ikon ikon-wallet"></em> Withdraw</a>
                        </li>
                        @endif
                        <li><a href="{{ route('user.account') }}"><em class="ikon ikon-user"></em> {{__('Profile')}}</a></li>
                        @if(gws('user_mytoken_page') == 1)
                        <li><a href="{{ route('user.token.balance') }}"><em class="ikon ikon-my-token"></em> {{ __('My Token') }}</a></li>
                        @endif
                        @if(gws('main_website_url') != NULL)
                        <li><a href="{{gws('main_website_url')}}" target="_blank"><em class="ikon ikon-home-link"></em> {{__('Main Site')}}</a></li>
                        @endif
                        <li><a href="{{  route('user.sell_goods.show') }}"><em class="fas fa-hand-holding-usd"></em> {{__('Sell Goods')}}</a></li>
                    </ul>
                   
                    <ul class="navbar-btns">
                        @if(!is_kyc_hide())
                        @if(isset(Auth::user()->kyc_info->status) && Auth::user()->kyc_info->status == 'approved')
                        <li><span class="badge badge-outline badge-success badge-lg"><em class="text-success ti ti-files mgr-1x"></em><span class="text-success">{{__('KYC Approved')}}</span></span></li>
                        @else
                        <li><a href="{{ route('user.kyc') }}" class="btn btn-sm btn-outline btn-light"><em class="text-primary ti ti-files"></em><span>{{__('KYC Application')}}</span></a></li>
                        @endif
                        @endif
                    </ul>
                   
                </div>{{-- .navbar-innr --}}
            </div>{{-- .container --}}
        </div>{{-- .navbar --}}
    </div>{{-- .topbar-wrap --}}

    <div class="page-content">
        <div class="container">
            <div class="row">
                @php
                $has_sidebar = isset($has_sidebar) ? $has_sidebar : false;
                $col_side_cls = ($has_sidebar) ? 'col-lg-4' : 'col-lg-12';
                $col_cont_cls = ($has_sidebar) ? 'col-lg-8' : 'col-lg-12';
                $col_cont_cls2 = isset($content_class) ? css_class($content_class) : null;
                $col_side_cls2 = isset($aside_class) ? css_class($aside_class) : null;
                @endphp

                <div class="main-content {{ empty($col_cont_cls2) ? $col_cont_cls : $col_cont_cls2 }}">
                    @if(!has_wallet() && gws('token_wallet_req')==1 && !empty(token_wallet()))
                    <div class="d-lg-none">
                        {!! UserPanel::add_wallet_alert() !!}
                    </div>
                    @endif
                    @yield('content')
                </div>

                @if ($has_sidebar==true)
                <div class="aside sidebar-right {{ empty($col_side_cls2) ? $col_side_cls : $col_side_cls2 }}">
                    @if(!has_wallet() && gws('token_wallet_req')==1 && !empty(token_wallet()))
                    <div class="d-none d-lg-block">
                        {!! UserPanel::add_wallet_alert() !!}
                    </div>
                    @endif
                    <div class="account-info card">
                        <div class="card-innr">
                            {!! UserPanel::user_account_status() !!}
                            @if(!empty(token_wallet()))
                            <div class="gaps-2-5x"></div>
                            {!! UserPanel::user_account_wallet() !!}
                            @endif
                        </div>
                    </div>
                    {!! (!is_page(get_slug('referral')) ? UserPanel::user_referral_info('') : '') !!}
                    {!! UserPanel::user_kyc_info('') !!}
                </div>{{-- .col --}}
                @else
                    @stack('sidebar')
                @endif

            </div>
        </div>{{-- .container --}}
    </div>{{-- .page-content --}}

    <div class="footer-bar">
        <div class="container">
            @if(is_show_social('site'))
            <div class="row justify-content-center">
                <div class="col-lg-5 text-center order-lg-last text-lg-right pdb-2x pb-lg-0">
                    {!! UserPanel::social_links() !!}
                </div>
                <div class="col-lg-7">
                    <div class="d-flex align-items-center justify-content-center justify-content-lg-start guttar-15px pdb-1-5x pb-lg-2">
                        {!! UserPanel::copyrights('div') !!}
                        {!! UserPanel::language_switcher() !!}
                    </div>
                    {!! UserPanel::footer_links(null, ['class'=>'align-items-center justify-content-center justify-content-lg-start']) !!}
                </div>
            </div>{{-- .row --}}
            @else 
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-7">
                    {!! UserPanel::footer_links(null, ['class'=>'guttar-20px']) !!}
                </div>
                <div class="col-lg-5 mt-2 mt-sm-0">
                    <div class="d-flex justify-content-between justify-content-lg-end align-items-center guttar-15px">
                        {!! UserPanel::copyrights('div') !!}
                        {!! UserPanel::language_switcher() !!}
                    </div>
                </div>
            </div>{{-- .row --}}
            @endif
        </div>{{-- .container --}}
    </div>{{-- .footer-bar --}}
    @yield('modals')
    <div id="ajax-modal"></div>
    <div class="page-overlay">
        <div class="spinner"><span class="sp sp1"></span><span class="sp sp2"></span><span class="sp sp3"></span></div>
    </div>

@if(gws('theme_custom'))
    <link rel="stylesheet" href="{{ asset(style_theme('custom')) }}">
@endif
    <script>
        var base_url = "{{ url('/') }}",
        {!! (has_route('transfer:user.send')) ? 'user_token_send = "'.route('transfer:user.send').'",' : '' !!}
        {!! (has_route('withdraw:user.request')) ? 'user_token_withdraw = "'.route('withdraw:user.request').'",' : '' !!}
        {!! (has_route('user.ajax.account.wallet')) ? 'user_wallet_address = "'.route('user.ajax.account.wallet').'",' : '' !!}
        csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>
    <script src="{{ asset('assets/js/jquery.bundle.js').css_js_ver() }}"></script>
    <script src="{{ asset('assets/js/script.js').css_js_ver() }}"></script>
    <script src="{{ asset('assets/js/app.js').css_js_ver() }}"></script>
    <script src="{{ asset('assets/js/tree.js').css_js_ver() }}"></script>
    <script src="{{ asset('assets/js/custom.js').css_js_ver() }}"></script>
    <!-- <script src="{{ asset('assets/plugins/swal/sweetalert2.all.min.js').css_js_ver() }}"></script> -->
    {{-- <script src="{{ asset('assets/js/sweetalert2.all.min.js').css_js_ver() }}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
    @yield('script')
    @stack('footer')
    <script type="text/javascript">
        @if (session('resent'))
        show_toast("success","{{ __('A fresh verification link has been sent to your email address.') }}");
        @endif
    </script>

    <script>
        var pushOneExchange = (one) => {
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want transfer ${one} ONE to Vikione.Exchange?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
                }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{route('user.one.exchange.deposit')}}"
                    window.location.href = url;
                }
            })

        }
    </script>
    @if(get_setting('site_footer_code', false))
    {{ html_string(get_setting('site_footer_code')) }}
    @endif
</body>
</html>