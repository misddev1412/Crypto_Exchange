@extends('layouts.auth')
@section('title', __('Welcome'))
@section('content')

<div class="{{ (gws('theme_auth_layout','default')=='center-dark'||gws('theme_auth_layout', 'default')=='center-light') ? 'page-ath-form' : 'page-ath-text' }}">
	<h2 class="page-ath-heading">{!! $text !!}
		@isset($subtext)
		<small>{!! $subtext !!}</small>
		@endisset
	</h2>

	@if (session('warning'))
	<div class="alert alert-dismissible fade show alert-warning" role="alert">
		<a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&nbsp;</a>
		{!! session('warning') !!}
	</div>
	@else
	@isset($msg)
	<div class="text-{{ isset($msg['type']) ? $msg['type'] : 'info' }}">{!! isset($msg['text']) ? $msg['text'] : $msg !!}</div>
	@endisset
	@endif
	<div class="gaps-4x"></div>
	@isset($hideButton)
	@else
	<p><a class="btn btn-primary" href="{{ route('login') }}">{{ __('Sign in') }}</a></p>
	@endisset
</div>
@endsection
