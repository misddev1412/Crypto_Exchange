<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="js">
<head>
    <meta charset="utf-8">
    <meta name="apps" content="{{ app_info() }}">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Not Found</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor.bundle.css').css_js_ver() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css').css_js_ver() }}">
</head>
@php 
$bg_img = " style=\"background-image:url('".asset('assets/images/bg-error.png')."'\"";
@endphp

<body class="page-error error-404 theme-modern"{!! $bg_img !!}>

    <div class="vh100 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7 col-xl-6 text-center">
                    <div class="error-content">
                        <span class="error-text-large">!!!</span>
                        @isset($heading)
                        <h4 class="text-dark">{{ $heading}}</h4>
                        @endisset
                        @isset($message)
                        <p>{{ $message }}</p>
                        @endisset
                        <a href="{{ url('/') }}?custom" class="btn btn-primary">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery.bundle.js').css_js_ver() }}"></script>
    <script src="{{ asset('assets/js/script.js').css_js_ver() }}"></script>
</body>
</html>