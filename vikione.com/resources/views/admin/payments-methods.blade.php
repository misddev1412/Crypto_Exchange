@extends('layouts.admin')
@section('title', 'Payment Methods')

@section('content')
<div class="page-content">
    <div class="container">
        @include('vendor.notice')
        <div class="card content-area">
            <div class="card-innr">
                <div class="card-head d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Payment Methods</h4>
                    <a href="javascript:void(0)" class="btn btn-sm btn-auto btn-outline btn-primary get_pm_manage" data-type="manage_currency"><em class="fas fa-coins"></em><span class=" d-sm-inline-block d-none">Manage Currency</span></a>
                </div>
                @if(is_demo_user() || is_demo_preview()) 
                    <div class="gaps-1-5x"></div>
                    <div class="alert alert-danger">{!! __('messages.demo_payment_note') !!}</div>
                @endif 
                <div class="gaps-1x"></div>
                <div class="row guttar-vr-30px{{ (empty($methods) ? ' justify-content-center' : '') }}">
                    @forelse($methods as $method)
                    <div class="col-xl-4 col-md-6">
                        {{ $method }}
                    </div>
                    @empty
                        <div class="bg-light text-center rounded pdt-5x pdb-5x">
                            <p><em class="ti ti-package fs-24"></em><br><strong class="mt-4 fs-20 text-head">Opps!</strong><br>No available payment module package!</p>
                            <p><a class="link" href="https://softnio.com/contact/" target="_blank">Contact us our support team.</a></p>
                        </div>
                    @endforelse
                </div>
                <div class="gaps-0x"></div>
            </div>
        </div>
    </div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection