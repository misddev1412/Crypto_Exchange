@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="offset-md-3 col-md-6">
                @component('components.card', [
                    'class' => 'lf-toggle-bg-card lf-toggle-border-color',
                    'headerClass' => "bg-primary text-white d-flex justify-content-between",
                    'footerClass' => "bg-primary text-white",
                ])
                    @slot('header')
                        <h4 class="card-title my-auto">
                            {{ __('Create Coin') }}
                        </h4>
                        <div class="card-link">
                            <a href="{{ route('coins.index') }}"
                               class="btn btn-info btn-sm back-button"><i class="fa fa-reply"></i></a>
                        </div>
                    @endslot

                    {{  Form::open(['route'=>'coins.store', 'method' => 'post', 'class'=>'form-horizontal validator', 'enctype'=>'multipart/form-data', 'id' => 'coinForm']) }}
                    @include('coins.admin._form')
                    {{ Form::close() }}
                @endcomponent
            </div>
        </div>
    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/jasny-bootstrap/css/jasny-bootstrap.min.css') }}">
    <style>
        .thumbnail {
            width: 100px;
            height: 100px;
        }
        .thumbnail img {
            max-width: 100%;
        }
        .thumbnail i {
            font-size: 50px;
        }
    </style>
@endsection
@section('script')
    @include('coins.admin._script')
    <script src="{{ asset('plugins/jasny-bootstrap/js/jasny-bootstrap.min.js') }}"></script>
@endsection
