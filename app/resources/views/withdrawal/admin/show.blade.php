@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <!-- WITHDRAWAK DETAILS -->
                <div class="card lf-toggle-bg-card lf-toggle-border-color p-4 mb-5">
                    <div class="card-header lf-toggle-border-color px-0 pb-4 pt-0">
                        <h3 class="w-card-title">
                            {{ $title }}
                        </h3>
                    </div>
                    <div class="card-body px-0 pt-4 pb-0">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="pl-0">{{__("User")}}</th>
                                    <td><a target="_blank"
                                           href="{{ route('admin.users.show', $withdrawal->user_id) }}">{{ $withdrawal->user->profile->full_name }}</a>
                                    </td>
                                    <th class="pl-0">{{ __('Amount') }}</th>
                                    <td>{{ bcsub($withdrawal->amount, $withdrawal->system_fee) }} {{ $withdrawal->symbol }}</td>
                                    <th class="pl-0">{{ __('Txn Id') }}</th>
                                    <td>{{ $withdrawal->txn_id ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="pl-0">{{__("Actual Amount")}}</th>
                                    <td>{{ $withdrawal->amount }}</td>
                                    @if($withdrawal->address)
                                        <th class="pl-0">{{ __('Address') }}</th>
                                        <td>{{ $withdrawal->address }}</td>
                                    @endif
                                    <th class="pl-0">{{__("Status")}}</th>
                                    <td>
                                        <span
                                            class="badge badge-{{ get_color_class($withdrawal->status, 'transaction_status')}}">
                                             {{ transaction_status($withdrawal->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class=" pl-0">
                                        {{__("Fee")}}</th>
                                    <td>{{ $withdrawal->system_fee ?: 'N/A' }}</td>
                                    <th class="pl-0">{{ __('Wallet') }}</th>
                                    <td>{{ $withdrawal->coin->name }} ({{ $withdrawal->symbol }})</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-right">
                @if(has_permission(replace_current_route_action('update')) && $withdrawal->status === STATUS_REVIEWING)
                    <a href="{{ route(replace_current_route_action('update'), ['withdrawal' => $withdrawal->id]) }}"
                       class="btn btn-info confirmation"
                       data-form-id="approve-{{ $withdrawal->id }}" data-form-method="PUT"
                       data-alert="{{__('Do you want to approve this withdrawal?')}}">{{ __('Approve') }}</a>
                @endif
                @if(has_permission(replace_current_route_action('destroy')) && in_array($withdrawal->status, [STATUS_REVIEWING, STATUS_PENDING]))
                    <a href="{{ route(replace_current_route_action('destroy'), ['withdrawal' => $withdrawal->id]) }}"
                       class="btn btn-danger confirmation"
                       data-form-id="cancel-{{ $withdrawal->id }}" data-form-method="DELETE"
                       data-alert="{{__('Do you want to cancel this withdrawal?')}}">{{ __('Cancel') }}</a>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('style')
    <style>
        .w-card-title {
            font-size: 20px;
            font-weight: 400;
            margin: 0;
        }

        .card-header {
            background-color: transparent;
        }

        .bank-card > .card-header {
            position: relative;
            padding-left: 90px;
        }

        .bank-card > .card-header::before {
            content: "\f19c";
            font-family: FontAwesome, serif;
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 72px;
            font-size: 28px;
            line-height: 72px;
            text-align: center;
            background-color: #105859;
            color: #fff;
            border-radius: 10px 0 0 0;
        }

        .bank-card .table td, .bank-card .table th {
            padding: 0.5rem 0;
        }

        .bank-card .table th {
            width: 35%;
        }

        .form-group.bank-deposit-btn > .btn {
            font-weight: bold;
            border-radius: 0 0 20px 20px !important;
            padding: 1rem;
            font-size: 20px;
        }
    </style>
@endsection
