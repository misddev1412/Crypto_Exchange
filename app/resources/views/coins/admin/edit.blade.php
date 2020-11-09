@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        @component('components.coin', ['coin' => $coin])
            {{  Form::model($coin, ['route'=>['coins.update', $coin->symbol], 'method' => 'put',
                        'class'=>'form-horizontal validator-form', 'enctype'=>'multipart/form-data', 'coinForm']) }}
            @include('coins.admin._form')
            {{ Form::close() }}
        @endcomponent
    </div>
@endsection

@section('style')
    @include('coins.admin._style')
    @include('layouts.includes._avatar_and_loader_style')
    <link rel="stylesheet" href="{{ asset('plugins/jasny-bootstrap/css/jasny-bootstrap.min.css') }}">
@endsection

@section('script')
    @include('coins.admin._script')
    <script src="{{ asset('plugins/jasny-bootstrap/js/jasny-bootstrap.min.js') }}"></script>
@endsection
