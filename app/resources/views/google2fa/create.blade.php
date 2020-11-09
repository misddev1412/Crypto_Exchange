@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        @component('components.profile', ['user' => $user])
            @if(!empty(Auth::user()->google2fa_secret))
                @include('google2fa._edit_form')
            @else
                @include('google2fa._create_form')
            @endif
        @endcomponent
    </div>
@endsection

@section('style')
    @include('layouts.includes._avatar_and_loader_style')
    <style>
        .qr-code-group {
            width: 250px;
        }
        .input-group.copy-group {
            margin: 7px auto;
        }
        .input-group.copy-group input {
            height: 30px;
            font-size: 12px !important;
        }
        .input-group.copy-group .btn {
            height: 30px !important;
            padding: 0 10px !important;
            font-size: 10px !important;
        }
    </style>
@endsection

@section('script')
    <script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
    <script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
    <script>
        "use strict";

        $(document).ready(function () {
            var form =$('#googleTwoFaForm').cValidate({
                rules : {
                    'password' : 'required',
                    'google_app_code' : 'required',
                }
            });
        });
        function copyLink() {
            var copyText = document.getElementById("link");
            copyText.select();
            document.execCommand("copy");
        }
    </script>
    <script src="{{ asset('plugins/jasny-bootstrap/js/jasny-bootstrap.min.js') }}"></script>
@endsection
