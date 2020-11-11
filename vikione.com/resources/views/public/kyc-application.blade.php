@extends('public.base')
@section('title', 'KYC Application')
@section('content')
@php
$has_sidebar = false;
@endphp
@section('content')
<div class="page-header page-header-kyc">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7 text-center">
            <h2 class="page-title">{{__('Begin your ID-Verification')}}</h2>
            <p class="large">{{__('Verify your identity to participate in token sale.')}}</p>
        </div>
    </div>
</div>

@include('layouts.messages')
<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
        <div class="kyc-form-steps card mx-lg-4">
            <input type="hidden" id="file_uploads" value="{{ route('public.kyc.file.upload') }}" />
            <form class="validate-modern" action="{{ route('public.kyc.submit') }}" method="POST" id="kyc_submit">
                @csrf
                @include('layouts.kyc-form')
            </form>
        </div>
    </div>
</div>
@endsection

@push('footer')
    <script src="{{ asset('assets/js/public.app.js').css_js_ver() }}"></script>
@endpush
