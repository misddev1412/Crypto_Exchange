@extends('layouts.master',['headerLess'=>true, 'activeSideNav' => active_side_nav()])

@section('title', __('Unknown Error'))

@section('content')
    @component('components.auth')
        <div class="mx-lg-4">
            <h2 class="text-center text-danger font-size-48">503</h2>
            <div class="mx-lg-4">
                <h4 class="text-center text-warning">{{ __('Be right back.') }}</h4>
            </div>
        </div>
    @endcomponent
@endsection
