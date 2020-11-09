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
                            {{ __('Edit System Bank Account') }}
                        </h4>
                        <div class="card-link">
                            <a href="{{ route('system-banks.index') }}"
                               class="btn btn-info btn-sm back-button"><i class="fa fa-reply"></i></a>
                        </div>
                    @endslot

                    {{ Form::model($systemBank, ['route'=>['system-banks.update', $systemBank->id], 'method' => 'put', 'id' => 'bankForm']) }}
                    @include('bank_managements.admin._form')
                    {{ Form::close() }}
                @endcomponent
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
    <script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
    <script>
        $(document).ready(function () {
            "use strict";

            $('.validator-form').cValidate({
                rules : {
                    'reference_number' : 'required|max:255',
                    'bank_name' : 'required|max:255',
                    'iban' : 'required|max:255',
                    'swift' : 'required|max:255',
                    'bank_address' : 'required|max:255',
                    'account_holder' : 'required|max:255',
                    'account_holder_address' : 'required|max:255',
                    'country_id' : 'required',
                    'is_active' : 'required',
                }
            });
        });
    </script>
@endsection
