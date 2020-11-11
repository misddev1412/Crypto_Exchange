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
    @if( recaptcha() )
    <script src="https://www.google.com/recaptcha/api.js?render={{ recaptcha('site') }}"></script>
    @endif
    @stack('header')
@if(get_setting('site_header_code', false))
    {{ html_string(get_setting('site_header_code')) }}
@endif
</head>

<body class="user-dashboard page-user theme-modern">
    <div class="topbar-wrap">
        <div class="topbar is-sticky">
            <div class="container">
                <div class="d-flex justify-content-center">
                    <a class="topbar-logo" href="{{url('/')}}">
                        <img height="40" src="{{ site_logo('default', 'light') }}" srcset="{{ site_logo('retina', 'light' ) }}" alt="{{ site_info() }}">
                    </a>
                </div>
            </div>{{-- .container --}}
        </div>{{-- .topbar --}}
    </div>{{-- .topbar-wrap --}}

    <div class="page-content">
        <div class="container">
            @yield('content') 
        </div>
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
                        <div class="copyright-text">{!! UserPanel::copyrights() !!}</div>
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
                        <div class="copyright-text">{!! UserPanel::copyrights() !!}</div>
                        {!! UserPanel::language_switcher() !!}
                    </div>
                </div>
            </div>{{-- .row --}}
            @endif
        </div>
    </div>{{-- .footer-bar --}}

    @yield('modals')
    <div id="ajax-modal"></div>
    <div class="page-overlay">
        <div class="spinner"><span class="sp sp1"></span><span class="sp sp2"></span><span class="sp sp3"></span></div>
    </div>
<script src="{{ asset('assets/js/jquery.bundle.js').css_js_ver() }}"></script>
<script src="{{ asset('assets/js/script.js').css_js_ver() }}"></script>
@stack('footer')
    <script type="text/javascript">
        var base_url = "{{ url('/') }}",
        csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        user_wallet_address = "{{ route('user.ajax.account.wallet') }}",
        layouts_style = "modern";

        @if (session('resent'))
        show_toast("success","{{ __('A fresh verification link has been sent to your email address.') }}");
        @endif
    </script>
    @if(get_setting('site_footer_code', false))
    {{ html_string(get_setting('site_footer_code')) }}
    @endif
</body>
</html>