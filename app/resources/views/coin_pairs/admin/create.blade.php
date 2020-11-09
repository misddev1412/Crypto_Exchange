@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="offset-md-2 col-md-8">
                @component('components.card', [
                    'class' => 'lf-toggle-bg-card lf-toggle-border-color',
                    'headerClass' => "bg-primary text-white d-flex justify-content-between",
                    'footerClass' => "bg-primary text-white",
                ])
                    @slot('header')
                        <h4 class="card-title my-auto">
                            {{ __('Create Coin Pair') }}
                        </h4>
                        <div class="card-link">
                            <a href="{{ route('coin-pairs.index') }}"
                               class="btn btn-info btn-sm back-button"><i class="fa fa-reply"></i></a>
                        </div>
                    @endslot

                    {{  Form::open(['route'=>'coin-pairs.store', 'method' => 'post', 'class'=>'form-horizontal validator-form', 'enctype'=>'multipart/form-data', 'id' => 'coinPairForm']) }}
                    @include('coin_pairs.admin._form')
                    {{ Form::close() }}
                @endcomponent
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('coin_pairs.admin._script')
@endsection
