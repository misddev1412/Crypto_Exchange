@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container py-5">
        @component('components.profile', ['user' => $user])
            @if(is_null($kycVerification))
                @include('kyc_management.user._form')
            @else
                @include('kyc_management.user._show')
            @endif
        @endcomponent
    </div>
@endsection
@section('style')
    @include('layouts.includes._avatar_and_loader_style')
    <link rel="stylesheet"
          href="{{ asset('plugins/jasny-bootstrap/css/jasny-bootstrap.min.css') }}">
    <style>
        .img-thumbnail {
            width: 150px;
            height: 150px;
            padding: 30px 0 !important;
        }
        .card-footer {
            background: rgba(0, 0, 0, 0.15);
            font-size: 18px;
            text-align: center;
        }
        .clickable {
            cursor: pointer;
        }
    </style>
@endsection
@section('script')
    <script src="{{ asset('plugins/jasny-bootstrap/js/jasny-bootstrap.min.js') }}"></script>
    <script>
        "use strict";

        new Vue({
            el: "#app",
            data: {
                step: 1,
                idType: false
            },
            methods: {
                nextStep: function (id) {
                    this.step = 2;
                    this.idType = id;
                },
                previousStep: function () {
                    this.step = 1;
                    this.idType = false;
                }
            }
        });
    </script>
@endsection
