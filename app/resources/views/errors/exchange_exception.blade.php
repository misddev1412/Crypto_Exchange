@extends('layouts.master',['headerLess'=>true, 'activeSideNav' => active_side_nav()])

@section('title', __('Coin pair not found.'))

@section('content')
    @component('components.auth')
        <h4 class="text-center text-danger mb-4">{{ __('Coin pair is not found.') }}</h4>
        @if(auth()->check() && auth()->user()->is_super_amdin)
            <p class="text-muted text-center">{{ __('Please configure a coin pair.') }}</p>
            <a href="{{ route('coin-pairs.create') }}" class="btn btn-info btn-block">{{ __('Add New Pair') }}</a>
        @else
            <p class="text-muted text-center">{{ __('Please contact to support center.') }}</p>
        @endif
        <a href="{{ route('home') }}"
           class="btn btn-success btn-block">{{ __('Go Home') }}</a>
    @endcomponent
@endsection
