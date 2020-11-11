@extends('layouts.auth')
@section('title', __('Sign up'))
@section('content')

<div class="page-ath-form">

    <h2 class="page-ath-heading pb-0">{{ __('Sign up') }}</h2>
    <div class="gaps-1x"></div>
    <p class="lead">{{ $notice }}</p>
    <form class="register-form validate validate-modern" method="POST" action="{{ route('social.register') }}" id="register">
        @csrf
        @include('layouts.messages')
        <div class="input-item">
            <input type="text" class="input-bordered{{ $errors->has('name') ? ' input-error' : '' }}" name="name" value="{{ $user->getName() }}" data-msg-required="{{ __('Required.') }}" required>
        </div>
        <div class="input-item">
            <input type="email" class="input-bordered{{ $errors->has('email') ? ' input-error' : '' }}" name="email" value="{{ $user->getEmail() }}" data-msg-required="{{ __('Required.') }}" data-msg-email="{{ __('Enter valid email.') }}" required>
        </div>
		<input type="hidden" name="social" value="{{ $social }}">
        <input type="hidden" name="social_id" value="{{ $user->getId() }}">
        @if(get_page_link('terms') || get_page_link('policy'))
        <div class="input-item text-left">
            <input name="terms" class="input-checkbox input-checkbox-md" id="agree" type="checkbox" required="required" data-msg-required="{{ __("You should accept our terms and policy.") }}">
            <label for="agree">{!! __('I agree to the') . ' ' .get_page_link('terms', ['target'=>'_blank', 'name' => true, 'status' => true]) . ((get_page_link('terms', ['status' => true]) && get_page_link('policy', ['status' => true])) ? ' '.__('and').' ' : '') . get_page_link('policy', ['target'=>'_blank', 'name' => true, 'status' => true]) !!}.</label>
        </div>
        @else
        <div class="input-item text-left">
           <label for="agree">{{ __('By registering you agree to the terms and conditions.') }}</label>
        </div>
        @endif
        <button type="submit" class="btn btn-primary btn-block">{{ __('Create Account') }}</button>
        <div class="gaps-1x"></div>
        <a href="{{ route('login') }}" class="btn-link">{{ __('Cancel Signup') }}</a>
    </form>
</div>
@endsection
