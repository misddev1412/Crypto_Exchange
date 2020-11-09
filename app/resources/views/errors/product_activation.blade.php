@extends('layouts.master',['headerLess'=>true])
@section('title', __('Production Activation'))
@section('content')
    @component('components.auth')
        <div class="mb-4">
            <h2 class="text-center text-danger mb-2">{{  __('Production Activation!')  }}</h2>
            <p class="text-center text-muted" style="font-size:16px ">{{ __('Product is expired or inactive. Please active it.') }}</p>
        </div>
        {{ Form::open(['route' => 'product-activation']) }}
        <div class="form-group">
            {{ Form::text('purchase_code', null, ['class' => form_validation($errors, 'purchase_code','bg-gray'), 'placeholder' => __('Enter Purchase Code')]) }}
            <span class="invalid-feedback">{{ $errors->first('purchase_code') }}</span>
        </div>
        <div class="form-group">
            {{ Form::submit(__('Activate'),['class' => 'btn btn-primary btn-block border lf-toggle-border-color font-weight-bold']) }}
        </div>
        {{ Form::close() }}
    @endcomponent

@endsection
