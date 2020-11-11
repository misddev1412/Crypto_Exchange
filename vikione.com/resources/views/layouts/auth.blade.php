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
@php 
$auth_layout = (gws('theme_auth_layout', 'default'));
$logo_light = ($auth_layout=='center-dark') ? 'logo-light' : 'logo';
$body_class = ($auth_layout=='center-dark'||$auth_layout=='center-light') ? ' page-ath-alt' : '';
$body_bgc   = ($auth_layout=='center-dark') ? ' bg-secondary' : '';
$wrap_class = ($auth_layout=='default') ? ' flex-row-reverse' : '';

$header_logo = '<div class="page-ath-header text-center" style="padding-top: 160px"><a href="'.url('/').'" class="page-ath-logo" ><img class="page-ath-logo-img" src="'. site_whitelabel($logo_light) .'" srcset="'. site_whitelabel($logo_light.'2x') .'" alt="'. site_whitelabel('name') .'" style="max-height:100%"></a></div>';
@endphp
<body class="page-ath theme-modern page-ath-modern{{ $body_class.$body_bgc }}">

    <div class="page-ath-wrap{{ $wrap_class }}">
        <div class="page-ath-content">
            {!! $header_logo !!}
            @yield('content')
            
            <div class="page-ath-footer">
                @if(is_show_social('login'))
                    {!! UserPanel::social_links('', ['class' => 'mb-3']) !!}
                    {!! UserPanel::footer_links(['lang' => true], ['class' => 'guttar-20px align-items-center']) !!}
                    {!! UserPanel::copyrights('div') !!}
                @else
                    {!! UserPanel::footer_links(['lang' => true, 'copyright'=>true], ['class' => 'guttar-20px align-items-center']) !!}
                @endif
            </div>
        </div>
        @if ($auth_layout=='default' || $auth_layout=='alter')
        <div class="page-ath-gfx" style="background-image: url({{ asset('images/ath-gfx.png') }});">
            <div class="w-100 d-flex justify-content-center">
                <div class="col-md-8 col-xl-5">
                    {{-- <img src="{{ asset('images/intro.png') }}" alt=""> --}}
                </div>
            </div>
        </div>
        @endif
    </div>

@if(gws('theme_custom'))
    <link rel="stylesheet" href="{{ asset(style_theme('custom')) }}">
@endif
    <script>
        var base_url = "{{ url('/') }}",
        csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        layouts_style = "modern";
    </script>
    <script src="{{ asset('assets/js/jquery.bundle.js').css_js_ver() }}"></script>
    <script src="{{ asset('assets/js/script.js').css_js_ver() }}"></script>
    <script type="text/javascript">
        jQuery(function(){
            var $frv = jQuery('.validate');
            if($frv.length > 0){ $frv.validate({ errorClass: "input-bordered-error error" }); }
        });
    </script>
    @stack('footer')

    @if(get_setting('site_footer_code', false))
    {{ html_string(get_setting('site_footer_code')) }}
    @endif
</body>
</html>