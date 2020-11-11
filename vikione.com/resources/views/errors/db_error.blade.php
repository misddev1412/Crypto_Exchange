<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="js">
<head>
    <meta charset="utf-8">
    <meta name="apps" content="{{ app_info() }}">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{(!empty($check_dt) && count($check_dt) >= 15 )? "Tokenlite Installation ":"Database Error"}}</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor.bundle.css').css_js_ver() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css').css_js_ver() }}">
</head>
@php 
$bg_img = " style=\"background-image:url('".asset('assets/images/bg-error.png')."'\"";

// dd($check_dt, $need_update);
@endphp

<body class="page-error error-404 theme-modern"{!! $bg_img !!}>

    <div class="vh100 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7 col-xl-5 text-center">
                    <div class="error-content">
                        @if(isset($check_dt) && count($check_dt) >= 15)  
                            <h4 class="text-dark mt-5">Tokenlite Database Installation</h4>
                            <p>It's look like you have connected database. But we have not found any database table of script. Would you like to install the database table.</p>
                            @if( isset($need_update) && $need_update > 0)
                            <a href="{{ route('LaravelUpdater::welcome') }}" class="btn btn-primary btn-outline">Run Update</a>
                            @else
                            <a href="{{ route('public.database') }}" class="btn btn-primary btn-outline">One Click to Install Demo Data</a>
                            @endif
                            <div class="gaps-2x"></div>
                            <p class="small text-danger">NB: This process can be harmfull or lost data, if your database have some data.</p>
                        @else  

                            @if( isset($need_update) && $need_update > 0)
                            <h4 class="text-dark mt-5">It's seems you had updated your application files to v{{ config('app.version') }}, to continue use of application require upgrade database. Please update your database.</h4>
                            <p><strong>NB: </strong> Keep backup your database before run update.</p>
                            <a href="{{ route('LaravelUpdater::welcome') }}" class="btn btn-primary btn-outline">Run Update</a>
                            @else
                            <h4 class="text-dark mt-5">Unable to connect with Database.</h4>
                            <p>Sorry we unable to connect with database server or it's tables. We try to resolved this issues. You may contact us our support team.</p>
                            @endif
                            <a href="{{ url('/') }}?db" class="btn btn-primary btn-outline">Back to Home</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery.bundle.js').css_js_ver() }}"></script>
    <script src="{{ asset('assets/js/script.js').css_js_ver() }}"></script>
</body>
</html>