@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-sm-12 col-md-4">
                <!-- Profile Image -->
                @include('core.profile.user_avatar', ['user' => $verification->user])
            </div>

            <div class="col-sm-12 col-md-8">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card lf-toggle-bg-card">
                            <div class="bg-primary text-white clearfix py-3 px-3">
                                <div class="float-left">
                                    <h3 class="card-title">{!!  __('ID Verification Request') !!}</h3>
                                </div>
                                @if(has_permission('admin.stock-items.index'))
                                    <div class="float-right">
                                        <a href="{{ route('kyc-management.index') }}"
                                           class="btn btn-info btn-sm back-button"><i class="fa fa-reply"></i></a>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body p-3">
                                <div class="form-horizontal show-form-data my-4">
                                    <div class="form-group row">
                                        <label class="col-sm-4 font-weight-bold">{{ __('ID Type') }}</label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static">{{ $verification->type ? kyc_type($verification->type) : '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 font-weight-bold">{{ __('ID Status') }}</label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static">
                                                <span class="badge badge-{{ config('commonconfig.kyc_status.' . $verification->status . '.color_class') }}">{{ kyc_status($verification->status) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    @if(!is_null($verification->reason))
                                    <div class="form-group row">
                                        <label class="col-sm-4 font-weight-bold">{{ __('Reason') }}</label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static">
                                                {{ $verification->reason }}
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <hr class="my-4">
                                <div class="my-4">
                                    @include('kyc_management.admin._show', ['user' => $verification])
                                </div>
                            </div>
                            <div class="card-footer my-4">
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        @if(has_permission('kyc-management.approve') && $verification->status == STATUS_REVIEWING)
                                            <a data-form-id="approve-{{ $verification->id }}"
                                               data-form-method="PUT"
                                               href="{{ route('kyc-management.approve', $verification->id) }}"
                                               class="confirmation btn btn-sm btn-success btn-flat btn-sm-block"
                                               data-alert="{{__('Do you want to approve this ID?')}}">
                                                {{ __('Approve') }}
                                            </a>
                                        @endif
                                        @if(has_permission('kyc-management.decline') && $verification->status == STATUS_REVIEWING)
                                            <div class="dropdown show d-inline-block">
                                                <a data-toggle="collapse"
                                                   data-target="#decline"
                                                   class="btn btn-danger btn-sm dropdown-toggle">
                                                    {{ __('Decline') }}
                                                </a>
                                            </div>
                                        @endif
                                        @if(has_permission('kyc-management.expired') && $verification->status == STATUS_VERIFIED )
                                            <div class="dropdown show d-inline-block">
                                                <a data-toggle="collapse"
                                                   data-target="#expired"
                                                   class="btn btn-danger dropdown-toggle">
                                                    {{ __('Expired') }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- reason form -->
                                    <div class="col-md-12">
                                        <!-- expired -->
                                        @if(has_permission('kyc-management.expired') && $verification->status == STATUS_VERIFIED )
                                            <div id="expired"
                                                 class="collapse border-top pt-4 mt-4">
                                                <form action="{{ route('kyc-management.expired', $verification->id) }}"
                                                      id="reasonForm"
                                                      method="post"
                                                      class="reason-form">
                                                    @csrf
                                                    @method('put')
                                                    <div class="form-group">
                                                        <lable for="reason"
                                                               class="col-form-label-sm mb-2 d-block">{{ __('Expired Reason') }}</lable>
                                                        <textarea name="reason"
                                                                  id="reason"
                                                                  cols="30"
                                                                  rows="5"
                                                                  class="form-control"></textarea>
                                                        <span class="invalid-feedback" data-name="reason">{{ $errors->first('reason') }}</span>
                                                    </div>
                                                    <input type="submit"
                                                           class="d-none">
                                                    <div class="form-group">
                                                        <a class="btn btn-sm btn-primary confirmation form-submission-button"
                                                           href="javascript:"
                                                           data-form-id="reasonForm"
                                                           data-alert="{{__('Do you really want to expired? The process will not be reversed')}}">{{ __('Submit') }}</a>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    <!-- decline -->
                                        @if(has_permission('kyc-management.decline') && $verification->status == STATUS_REVIEWING)
                                            <div id="decline"
                                                 class="collapse border-top pt-4 mt-4">
                                                <form action="{{ route('kyc-management.decline', $verification->id) }}"
                                                      id="reasonForm"
                                                      method="post"
                                                      class="reason-form">
                                                    @csrf
                                                    @method('put')
                                                    <div class="form-group">
                                                        <lable for="reason"
                                                               class="col-form-label-sm mb-2 d-block">{{ __('Decline Reason') }}</lable>
                                                        <textarea name="reason"
                                                                  id="reason"
                                                                  cols="30"
                                                                  rows="5"
                                                                  class="form-control"></textarea>
                                                        <span class="invalid-feedback" data-name="reason">{{ $errors->first('reason') }}</span>
                                                    </div>
                                                    <input type="submit"
                                                           class="d-none">
                                                    <div class="form-group">
                                                        <a class="btn btn-sm btn-primary confirmation form-submission-button"
                                                           href="javascript:"
                                                           data-form-id="reasonForm"
                                                           data-alert="{{__('Do you really want to decline? The process will not be reversed')}}">{{ __('Submit') }}</a>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after-style')
    <style>
        .user-info {
            padding: 15px;
        }

        .maginless {
            margin: 0;
        }
    </style>
@endsection

@section('script')
    <script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
    <script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
    <script>
        "use strict";

        $(document).ready(function () {
            var form =$('#reasonForm').cValidate({
                rules : {
                    'reason' : 'required|escapeInput|min:0|max:255',
                }
            });
        });

        new Vue({
            el: "#app"
        });
    </script>
@endsection
