@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        @component('components.profile', ['user' => $user])
            @if($user->referral_code)
                <div class="input-group">
                    <input type="text" class="form-control form-control-sm" id="link" readonly value="{{ route('register.index',['ref' => $user->referral_code ]) }}">
                    <button class="btn btn-sm btn-info" title="{{ __('Copy Link') }}" data-toggle="tooltip" type="button" onclick="copyLink()"><i class="fa fa-clipboard text-aqua"></i></button>
                </div>
            @endif
        @endcomponent
    </div>
@endsection
@section('style')
    @include('layouts.includes._avatar_and_loader_style')
@endsection
@section('script')
    <script>
        "use strict";

        function copyLink() {
            var copyText = document.getElementById("link");
            copyText.select();
            document.execCommand("copy");
        }
    </script>
@endsection
