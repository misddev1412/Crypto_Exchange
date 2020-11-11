<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="js">
<head>
    <meta charset="utf-8">
    <meta name="apps" content="{{ app_info() }}">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Website Under Maintenance</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor.bundle.css').css_js_ver() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css').css_js_ver() }}">
</head>
@php 
$bg_img = "";
@endphp

<body class="page-offline theme-modern"{!! $bg_img !!}>

    <div class="vh100 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7 col-xl-6 text-center">
                    <div class="offline-content">
                        <h1 class="text-primary">System is under maintenance</h1>
                        <h4 class="text-light">{!! get_setting('site_maintenance_text', 'We are doing some routine update on our site, please be patient we will back soon.') !!}</h4>
                        <p>Please contact us{!! (site_info('email')) ? ' at <a href="mailto:'.site_info('email').'">'.site_info('email').'</a>' : '' !!} for more information.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery.bundle.js').css_js_ver() }}"></script>
    <script src="{{ asset('assets/js/script.js').css_js_ver() }}"></script>
</body>
</html>