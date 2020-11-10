@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container py-5" id="app">
        <div class="row d-flex justify-content-center">
            <div class="col-md-7 text-light">

                @if( isset($token) )
                    <div class="card lf-toggle-bg-card lf-toggle-border-color">
                        <div class="card-header">
                            <h4 class="card-title lf-toggle-text-color text-center">{{ __('Personal Access Token') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-size-12">{{ __('Token Name') }}</label>
                                <div class="col-sm-9">{{ $token->accessToken->name }}</div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-size-12">{{ __('Access Token') }}</label>
                                <div class="col-sm-9 pt-1">
                                    <div class="d-flex justify-content-center">
                                        <figcaption class="border line-height-maximum px-2" id="copyToken">{{ $token->plainTextToken }}</figcaption>
                                        <button class="btn btn-sm btn-primary py-1" id="copyTokenBtn">{{ __('Copy') }}</button>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-warning" role="alert">
                                {{ __('Waring: This token appears only one time. Please take backup before leaving this page.') }}
                            </div>

                            <div class="text-center">
                                <a href="{{ route('personal-access-tokens.index') }}" class="btn btn-sm btn-info">
                                    {{ __('I got it and Go back') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card text-center lf-toggle-bg-card lf-toggle-border-color">
                        <div class="card-header">
                            <h4 class="card-title lf-toggle-text-color">{{ __('Create Personal Access Token') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ Form::open(['route'=>['personal-access-tokens.store'], 'method' => 'post', 'class'=>'form-horizontal validator dark-text-color', 'id' => 'create-access-token']) }}
                            {{--token_name--}}
                            <div class="form-group {{ $errors->has('token_name') ? 'has-error' : '' }}">
                                <label for="token_name"
                                       class="control-label required lf-toggle-text-color">{{ __('Token Name') }}</label>
                                <div>
                                    {{ Form::text('token_name',  old('token_name', null), ['class'=>'form-control lf-toggle-bg-input lf-toggle-border-color text-center', 'id' =>'amount', 'placeholder' => __('Token Name')]) }}
                                    <span class="invalid-feedback"
                                          data-name="amount">{{ $errors->first('token_name') }}</span>
                                </div>
                            </div>

                            {{--submit button--}}
                            <div class="form-group">
                                {{ Form::submit(__('Create Token'), ['class'=>'btn btn-info form-submission-button']) }}
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
    <script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
    <script>
        $(document).ready(function () {
            "use strict";
            @if( isset($token) )
                let copyTokenBtn = $("#copyTokenBtn");

                copyTokenBtn.on("click", function () {
                    copyToClipboard("#copyToken");
                });

                function copyToClipboard(element) {
                    let $temp = $("<input>");
                    $("body").append($temp);
                    $temp.val($(element).html()).select();
                    document.execCommand("copy");
                    $temp.remove();
                }
            @else
                $('#create-access-token').cValidate({
                    rules: {
                        'token_name': 'required|max:255',
                    }
                });
            @endif
        });
    </script>
@endsection
