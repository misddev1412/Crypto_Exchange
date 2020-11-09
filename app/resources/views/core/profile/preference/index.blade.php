@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        @component('components.profile', ['user' => $user])
            <div class="table-responsive-sm">
                <table class="table table-borderless font-size-14">
                    <tbody>
                    <tr>
                        <td>{{ __('Display Language') }}</td>
                        <td>
                            <strong class="pr-3">:</strong>
                            {{ $preference->display_language ? $preference->language->short_code : get_default_language()}}
                        </td>
                    </tr>
                    <tr>
                        <td>{{ __('Default Exchange') }}</td>
                        <td>
                            <strong class="pr-3">:</strong>
                            {{ $preference->default_coin_pair ? $preference->exchange->name : get_default_exchange() }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            @slot('button')
                <a href="{{ route('preference.edit') }}"
                   class="btn lf-card-btn btn-info btn-sm-block">{{ __('Change Preference') }}</a>
            @endslot
        @endcomponent
    </div>
@endsection
@section('style')
    @include('layouts.includes._avatar_and_loader_style')
@endsection
