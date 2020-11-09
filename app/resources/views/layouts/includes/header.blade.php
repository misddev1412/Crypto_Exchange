<!doctype html>
<html lang="en"
      class="{{isset($headerLess) && $headerLess && settings('no_header_layout') ? ' no-header-light' : ''}}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge">
    <meta name="csrf-token"
          content="{{ csrf_token() }}">
    <link rel="icon"
          href="{{ get_favicon() }}">
    @yield('meta')

    <title>
        @hasSection('title')
            @yield('title', config('app.name')) | {{ config('app.name') }}
        @else
            {{ config('app.name') }}
        @endif
    </title>

    @yield('style-top')
    <link rel="stylesheet" href="{{ asset('plugins/icofont/icofont.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700"
          rel="stylesheet">
    @if(!isset($headerLess) || !$headerLess)
        <link rel="stylesheet"
              href="{{ asset('plugins/slicknav/slicknav.min.css') }}">
        <link rel="stylesheet"
              href="{{ asset('plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.css') }}">
    @endif
    <link rel="stylesheet"
          href="{{ asset('css/app.css') }}">
    @yield('style')
</head>
<body
    class="{{((!isset($activeSideNav) && in_array(settings('navigation_type'), [1,2])) || (isset($activeSideNav) && $activeSideNav)) ? (isset($fixedSideNav) ? ($fixedSideNav ? 'lf-fixed-sidenav' : '') : (settings('navigation_type') && settings('side_nav_fixed') ? 'lf-fixed-sidenav' : '')) : ''}}{{isset($headerLess) && $headerLess ? ' lf-headerless-body' : ''}} {{ is_light_mode('light', 'dark') }}"
>
<div id="app"
     class="wrapper{{((!isset($activeSideNav) && in_array(settings('navigation_type'), [1,2])) || (isset($activeSideNav) && $activeSideNav)) ? (isset($fixedSideNav) ? ($fixedSideNav ? ' lf-fixed-sidenav' : '') : (settings('navigation_type') && settings('side_nav_fixed') ? ' lf-fixed-sidenav-wrapper' : '')) : ''}}">
