@extends('layouts.master',['headerLess'=>true, 'activeSideNav' => active_side_nav()])
@section('title', __('Under Maintenance'))
@section('content')
    @component('components.auth')
        <div class="mx-lg-4">
            <h2 class="text-center text-danger font-size-48">{{ __('Under Maintenance')  }}</h2>
            <p class="text-center">{{ __("The website is still under maintenance mode. send us an email anytime :email",['email' => settings('admin_receive_email')])}}</p>
        </div>
    @endcomponent
@endsection
