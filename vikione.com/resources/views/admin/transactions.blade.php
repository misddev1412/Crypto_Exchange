@extends('layouts.admin')
@section('title', ucfirst($is_page).' Transactions')

@section('content')
<div class="page-content">
    <div class="container">
        @include('layouts.messages')
        @include('vendor.notice')
        <div class="card content-area content-area-mh">
            <div class="card-innr">
                <div class="card-head has-aside">
                    <h4 class="card-title">{{ ucfirst($is_page) }} Transactions</h4>
                    <div class="card-opt">
                        <ul class="btn-grp btn-grp-block guttar-20px">
                            <li>
                                <a href="#" class="btn btn-sm btn-auto btn-primary" data-toggle="modal" data-target="#addTnx">
                                    <em class="fas fa-plus-circle"></em><span>Add <span class="d-none d-sm-inline-block">Tokens</span></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="page-nav-wrap">
                    <div class="page-nav-bar justify-content-between bg-lighter">
                        <div class="page-nav w-100 w-lg-auto">
                            <ul class="nav">
                                <li class="nav-item{{ (is_page('transactions.pending') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.transactions', 'pending') }}">Pending</a></li>
                                <li class="nav-item {{ (is_page('transactions.approved') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.transactions', 'approved') }}">Approved</a></li>
                                <li class="nav-item {{ (is_page('transactions.bonuses') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.transactions', 'bonuses') }}">Bonuses</a></li>
                                <li class="nav-item {{ (is_page('transactions') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.transactions') }}">All</a></li>
                            </ul>
                        </div>
                        <div class="search flex-grow-1 pl-lg-4 w-100 w-sm-auto">
                            <form action="{{ route('admin.transactions') }}" method="GET" autocomplete="off">
                                <div class="input-wrap">
                                    <span class="input-icon input-icon-left"><em class="ti ti-search"></em></span>
                                    <input type="search" class="input-solid input-transparent" placeholder="Tranx ID to quick search" value="{{ request()->get('s', '') }}" name="s">
                                </div>
                            </form>
                        </div>
                        @if(!empty(env_file()) && nio_status() && !empty(app_key()))
                        <div class="tools w-100 w-sm-auto">
                            <ul class="btn-grp guttar-8px">
                                <li><a href="#" class="btn btn-light btn-sm btn-icon btn-outline bg-white advsearch-opt"> <em class="ti ti-panel"></em> </a></li>
                                @if(is_super_admin())
                                <li>
                                    <div class="relative">
                                        <a href="#" class="btn btn-light bg-white btn-sm btn-icon toggle-tigger btn-outline"><em class="ti ti-server"></em> </a>
                                        <div class="toggle-class dropdown-content dropdown-content-sm dropdown-content-center shadow-soft">
                                            <ul class="dropdown-list">
                                                <li><h6 class="dropdown-title">Export</h6></li>
                                                <li><a href="{{ route('admin.export', array_merge([ 'table' => 'transactions', 'format' => 'entire'], request()->all())) }}">Entire</a></li>
                                                <li><a href="{{ route('admin.export', array_merge([ 'table' => 'transactions', 'format' => 'minimal'], request()->all())) }}">Minimal</a></li>
                                                <li><a href="{{ route('admin.export', array_merge([ 'table' => 'transactions', 'format' => 'compact'], request()->all())) }}">Compact</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                <li>
                                    <div class="relative">
                                        <a href="#" class="btn btn-light bg-white btn-sm btn-icon toggle-tigger btn-outline"><em class="ti ti-settings"></em> </a>
                                        <div class="toggle-class dropdown-content dropdown-content-sm dropdown-content-center shadow-soft">
                                            <form class="update-meta" action="#" data-type="tnx_page_meta">
                                                <ul class="dropdown-list">
                                                    <li><h6 class="dropdown-title">Show</h6></li>
                                                    <li{!! (gmvl('tnx_per_page', 10)==10) ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="perpage=10">10</a></li>
                                                    <li{!! (gmvl('tnx_per_page', 10)==20) ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="perpage=20">20</a></li>
                                                    <li{!! (gmvl('tnx_per_page', 10)==50) ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="perpage=50">50</a></li>
                                                </ul>
                                                <ul class="dropdown-list">
                                                    <li><h6 class="dropdown-title">Order</h6></li>
                                                    <li{!! (gmvl('tnx_ordered', 'DESC')=='DESC') ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="ordered=DESC">DESC</a></li>
                                                    <li{!! (gmvl('tnx_ordered', 'DESC')=='ASC') ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="ordered=ASC">ASC</a></li>
                                                </ul>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        @endif
                    </div>
                    @if( !empty(env_file()) && nio_status() && !empty(app_key()) )
                    <div class="search-adv-wrap hide">
                        <form class="adv-search" id="adv-search" action="{{ route('admin.transactions') }}" method="GET" autocomplete="off">
                            <div class="row align-items-end guttar-20px guttar-vr-15px">
                                <div class="col-lg-6">
                                    <div class="input-grp-wrap">
                                        <span class="input-item-label input-item-label-s2 text-exlight">Advanced Search</span>
                                        <div class="input-grp align-items-center bg-white">
                                            <div class="input-wrap flex-grow-1">
                                                <input value="{{ request()->get('search') }}" class="input-solid input-solid-sm input-transparent" type="text" placeholder="Search by ID" name="search">
                                            </div>
                                            <ul class="search-type">
                                                <li class="input-wrap input-radio-wrap">
                                                    <input name="by" class="input-radio-select" id="advs-by-tnx" value="" type="radio"{{ (empty(request()->by) || request()->by!='usr') ? ' checked' : '' }}>
                                                    <label for="advs-by-tnx">TRANX</label>
                                                </li>
                                                <li class="input-wrap input-radio-wrap">
                                                    <input name="by" class="input-radio-select" id="advs-by-user" value="usr" type="radio"{{ (isset(request()->by) && request()->by=='usr') ? ' checked' : '' }}>
                                                    <label for="advs-by-user">User</label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-4 col-mb-6">
                                    <div class="input-wrap input-with-label">
                                        <label class="input-item-label input-item-label-s2 text-exlight">Tranx Type</label>
                                        <select  name="type" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                            <option value="">Any Type</option>
                                            <option {{ request()->get('type') == 'purchase' ? 'selected' : '' }} value="purchase">Purchase</option>
                                            <option {{ request()->get('type') == 'bonus' ? 'selected' : '' }} value="bonus">Bonus</option>
                                            <option {{ request()->get('type') == 'referral' ? 'selected' : '' }} value="referral">Referral</option>
                                            <option {{ request()->get('type') == 'transfer' ? 'selected' : '' }} value="transfer">Transfer</option>
                                            <option {{ request()->get('type') == 'refund' ? 'selected' : '' }} value="refund">Refund</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-4 col-mb-6">
                                    <div class="input-wrap input-with-label">
                                        <label class="input-item-label input-item-label-s2 text-exlight">Status</label>
                                        <select name="state" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                            <option value="">Show All</option>
                                            <option {{ request()->get('state') == 'pending' ? 'selected' : '' }} value="pending">Pending</option>
                                            <option {{ request()->get('state') == 'onhold' ? 'selected' : '' }} value="onhold">Onhold</option>
                                            <option {{ request()->get('state') == 'approved' ? 'selected' : '' }} value="approved">Approved</option>
                                            <option {{ request()->get('state') == 'canceled' ? 'selected' : '' }} value="canceled">Canceled</option>
                                            <option {{ request()->get('state') == 'deleted' ? 'selected' : '' }} value="deleted">Deleted</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-4 col-mb-6">
                                    <div class="input-wrap input-with-label">
                                        <label class="input-item-label input-item-label-s2 text-exlight">Stage</label>
                                        <select name="stg" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                            <option value="">All Stage</option>
                                            @forelse($stages as $stage)
                                            <option {{ request()->get('stg') == $stage->id ? 'selected' : '' }} value="{{ $stage->id }}">{{ $stage->name }}</option>
                                            @empty
                                            <option value="">No active stage</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-2 col-mb-6">
                                    <div class="input-wrap input-with-label">
                                        <label class="input-item-label input-item-label-s2 text-exlight">Pay Method</label>
                                        <select name="pmg" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                            <option value="">All</option>
                                            @foreach($gateway as $pmg)
                                            <option {{ request()->get('pmg') == $pmg ? 'selected' : '' }} value="{{ $pmg }}">{{ ucfirst($pmg) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-2 col-mb-6">
                                    <div class="input-wrap input-with-label">
                                        <label class="input-item-label input-item-label-s2 text-exlight">Pay Currency</label>
                                        <select name="pmc" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                            <option value="">All</option>
                                            @foreach($pm_currency as $gt => $full)
                                            @if(token('purchase_'.$gt) == 1)
                                            <option {{ request()->get('pmc') == $gt ? 'selected' : '' }} value="{{ strtolower($gt) }}">{{ strtoupper($gt) }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-2 col-mb-6">
                                    <div class="input-wrap input-with-label">
                                        <label class="input-item-label input-item-label-s2 text-exlight">Date Within</label>
                                        <select name="date" class="select select-sm select-block select-bordered date-opt" data-dd-class="search-off">
                                            <option value="">All Time</option>
                                            <option {{ request()->get('date') == 'today' ? 'selected' : '' }} value="today">Today</option>
                                            <option {{ request()->get('date') == 'this-month' ? 'selected' : '' }} value="this-month">This Month</option>
                                            <option {{ request()->get('date') == 'last-month' ? 'selected' : '' }} value="last-month">Last Month</option>
                                            <option {{ request()->get('date') == '90day' ? 'selected' : '' }} value="90day">Last 90 Days</option>
                                            <option {{ request()->get('date') == 'custom' ? 'selected' : '' }} value="custom">Custom Range</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-2 col-mb-6 date-hide-show">
                                    <div class="input-wrap input-with-label">
                                        <label class="input-item-label input-item-label-s2 text-exlight">From</label>
                                        <div class="relative">
                                            <input class="input-bordered input-solid-sm date-picker bg-white" value="{{ (request()->get('date') == 'custom') ? request()->get('from') : '' }}" type="text" id="date-from" name="from" data-format="alt">
                                            <span class="input-icon input-icon-right date-picker-icon"><em class="ti ti-calendar"></em></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-2 col-mb-6 date-hide-show">
                                    <div class="input-wrap input-with-label">
                                        <label class="input-item-label input-item-label-s2 text-exlight">To</label>
                                        <div class="relative">
                                            <input class="input-bordered input-solid-sm date-picker bg-white" value="{{ (request()->get('date') == 'custom') ? request()->get('to') : '' }}" type="text" id="date-to" name="to" data-format="alt">
                                            <span class="input-icon input-icon-right date-picker-icon"><em class="ti ti-calendar"></em></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-2 col-mb-6">
                                    <div class="input-wrap">
                                        <input type="hidden" name="filter" value="1">
                                        <button class="btn btn-sm btn-sm-s2 btn-auto btn-primary">
                                            <em class="ti ti-search width-auto"></em><span>Search</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif

                    @if (request()->get('filter') || request()->s)
                    <div class="search-adv-result">
                        <div class="search-info">
                            Found <span class="search-count">{{ $trnxs->total() }}</span> Transactions{{ (request()->get('date') != 'custom') ? '.' : '' }}
                            @if (request()->get('date') == 'custom')
                            between <span>{{ _date(request()->get('from'), '', true) }}</span> to <span>{{ _date(request()->get('to'), '', true) }}</span>.
                            @endif
                        </div>
                        <ul class="search-opt">
                            @if(request()->get('search'))
                            <li><a href="{{ qs_url(qs_filter('search')) }}">Search <span>'{{ request()->get('search') }}'</span>{{ (request()->by=='usr') ? ' (User)' : '' }}</a></li>
                            @endif

                            @if(request()->get('type'))
                                <li><a href="{{ qs_url( qs_filter('type')) }}">Type: <span>{{ ucfirst(request()->get('type')) }}</span></a></li>
                            @endif

                            @if(request()->get('state'))
                                <li><a href="{{ qs_url( qs_filter('state')) }}">Status: <span>{{ ucfirst(request()->get('state')) }}</span></a></li>
                            @endif

                            @if(request()->get('stg'))
                                <li><a href="{{ qs_url( qs_filter('stg')) }}">Stage: <span>{{ ucfirst(request()->get('stg')) }}</span></a></li>
                            @endif

                            @if(request()->get('pmg'))
                                <li><a href="{{ qs_url( qs_filter('pmg')) }}">Pay Method: <span>{{ ucfirst(request()->get('pmg')) }}</span></a></li>
                            @endif

                            @if(request()->get('pmc'))
                                <li><a href="{{ qs_url( qs_filter('pmc')) }}">Currency: <span>{{ strtoupper(request()->get('pmc')) }}</span></a></li>
                            @endif

                            @if (request()->get('date') == 'today')
                                <li><a href="{{ qs_url( qs_filter('date')) }}">In today</span></a></li>
                            @endif

                            @if (request()->get('date') == 'this-month')
                                <li><a href="{{ qs_url( qs_filter('date')) }}"><span>In this month</span></a></li>
                            @endif

                            @if (request()->get('date') == 'last-month')
                                <li><a href="{{ qs_url( qs_filter('date')) }}"><span>In last month</span></a></li>
                            @endif

                            @if (request()->get('date') == '90day')
                                <li><a href="{{ qs_url( qs_filter('date')) }}"><span>In last 90 days</span></a></li>
                            @endif
                            <li><a href="{{ route('admin.transactions') }}" class="link link-underline">Clear All</a></li>
                        </ul>
                    </div>
                    @endif
                </div>
                
                @if($trnxs->total() > 0) 
                <table class="data-table admin-tnx">
                    <thead>
                        <tr class="data-item data-head">
                            <th class="data-col tnx-status dt-tnxno">Tranx ID</th>
                            <th class="data-col dt-token">Tokens</th>
                            <th class="data-col dt-amount">Amount</th>
                            <th class="data-col dt-usd-amount">Base Amount</th>
                            <th class="data-col dt-token">Point</th>
                            <th class="data-col pm-gateway dt-account">Pay From</th>
                            <th class="data-col dt-type tnx-type">Type</th>
                            <th class="data-col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trnxs as $trnx)
                        @php 
                            $text_danger = ( $trnx->tnx_type=='refund' || ($trnx->tnx_type=='transfer' && $trnx->extra=='sent') ) ? ' text-danger' : '';
                        @endphp
                        <tr class="data-item" id="tnx-item-{{ $trnx->id }}">
                            <td class="data-col dt-tnxno">
                                <div class="d-flex align-items-center">
                                    <div id="ds-{{ $trnx->id }}" data-toggle="tooltip" data-placement="top" title="{{ __status($trnx->status, 'text') }}" class="data-state data-state-{{ __status($trnx->status, 'icon') }}">
                                        <span class="d-none">{{ ucfirst($trnx->status) }}</span>
                                    </div>
                                    <div class="fake-class">
                                        <span class="lead tnx-id">{{ $trnx->tnx_id }}</span>
                                        <span class="sub sub-date">{{ _date($trnx->tnx_time) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="data-col dt-token">
                                <span class="lead token-amount{{ $text_danger }}">{{ (starts_with($trnx->total_tokens, '-') ? '' : '+').$trnx->total_tokens }}</span>
                                <span class="sub sub-symbol">{{ token_symbol() }} {!! (($trnx->tnx_type != 'purchase') ? 'BLUE' : 'GOLD') !!}</span>
                            </td>
                            <td class="data-col dt-amount">
                                @if ($trnx->tnx_type=='referral'||$trnx->tnx_type=='bonus') 
                                    <span class="lead amount-pay">{{ '~' }}</span>
                                @else 
                                <span class="lead amount-pay{{ $text_danger }}">{{ to_num($trnx->amount, 'max') }}</span>
                                <span class="sub sub-symbol">{{ strtoupper($trnx->currency) }} <em class="fas fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="1 {{ token('symbol') }} = {{ to_num($trnx->currency_rate, 'max').' '.strtoupper($trnx->currency) }}"></em></span>
                                @endif
                            </td>
                            <td class="data-col dt-usd-amount{{ $text_danger }}">
                                @if ($trnx->tnx_type=='referral'||$trnx->tnx_type=='bonus') 
                                    <span class="lead amount-receive">{{ '~' }}</span>
                                @else 
                                <span class="lead amount-receive{{ $text_danger }}">{{ to_num($trnx->base_amount, 'auto') }}</span>
                                <span class="sub sub-symbol">{{ strtoupper($trnx->base_currency) }} <em class="fas fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="1 {{ token('symbol') }} = {{ to_num($trnx->base_currency_rate, 'max').' '.strtoupper($trnx->base_currency) }}"></em></span>
                                @endif
                            </td>
                            <td class="data-col dt-point">
                                <span class="lead amount-pay{{ $text_danger }}">{{ to_num($trnx->point, 'auto') }}</span>
                                <span class="sub sub-symbol">POINT</span>
                               
                            </td>
                            <td class="data-col dt-account">
                                <span class="sub sub-s2 pay-with">
                                    @if ($trnx->tnx_type=='bonus' && $trnx->added_by!=set_added_by('0')) 
                                        {{ 'Added by '.transaction_by($trnx->added_by) }}
                                    @elseif($trnx->tnx_type == 'refund')
                                        {{ $trnx->details }}
                                    @elseif($trnx->tnx_type == 'transfer')
                                        {{ $trnx->details }}
                                    @else
                                        {{ (is_gateway($trnx->payment_method, 'internal') ? gateway_type($trnx->payment_method, 'name') : ( (is_gateway($trnx->payment_method, 'online') || $trnx->payment_method=='bank') ? 'Pay via '.ucfirst($trnx->payment_method) : 'Pay with '.strtoupper($trnx->currency) ) ) }}
                                        @if($trnx->wallet_address && $trnx->tnx_type!='bonus')
                                        <em class="fas fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="{{ $trnx->wallet_address }}"></em>
                                        @endif
                                    @endif
                                </span>
                                @if($trnx->tnx_type == 'refund')
                                    @php 
                                    $extra = (is_json($trnx->extra, true) ?? $trnx->extra);
                                    @endphp
                                    <span class="sub sub-email"><a href="{{ route('admin.transactions.view', ($extra->trnx ?? $trnx->id)) }}">View Transaction</a></span>
                                @else
                                    <span class="sub sub-email">{{ set_id($trnx->user) }} <em class="fas fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="{{ isset($trnx->tnxUser) ? explode_user_for_demo($trnx->tnxUser->email, auth()->user()->type) : '' }}"></em></span> 
                                @endif
                            </td>
                            <td class="data-col data-type">
                                <span class="dt-type-md badge badge-outline badge-md badge-{{$trnx->id}} badge-{{__status($trnx->tnx_type,'status')}}">{{ ucfirst($trnx->tnx_type) }}</span>
                                <span class="dt-type-sm badge badge-sq badge-outline badge-md badge-{{$trnx->id}} badge-{{__status($trnx->tnx_type,'status')}}">{{ ucfirst(substr($trnx->tnx_type, 0, 1)) }}</span>
                            </td>
                            <td class="data-col text-right">
                                @if($trnx->status == 'deleted')
                                <a href="{{ route('admin.transactions.view', $trnx->id) }}" target="_blank" class="btn btn-light-alt btn-xs btn-icon"><em class="ti ti-eye"></em></a>
                                @else 
                                <div class="relative d-inline-block">
                                    <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger"><em class="ti ti-more-alt"></em></a>
                                    <div class="toggle-class dropdown-content dropdown-content-top-left">
                                        <ul id="more-menu-{{ $trnx->id }}" class="dropdown-list">
                                            <li><a href="{{ route('admin.transactions.view', $trnx->id) }}">
                                                <em class="ti ti-eye"></em> View Details</a></li>
                                            @if( $trnx->tnx_type == 'transfer' && $trnx->status == 'pending')
                                            <li><a href="javascript:void(0)" class="tnx-transfer-action" data-status="approved" data-tnx_id="{{ $trnx->id }}">
                                                <em class="far fa-check-square"></em> Approve</a></li>
                                            <li><a href="javascript:void(0)" class="tnx-transfer-action" data-status="rejected" data-tnx_id="{{ $trnx->id }}">
                                                <em class="fas fa-ban"></em> Reject</a></li>
                                            @endif
                                            @if($trnx->status == 'approved' && $trnx->tnx_type == 'purchase' && $trnx->refund == null)
                                            <li><a href="javascript:void(0)" class="tnx-action" data-type="refund" data-id="{{ $trnx->id }}">
                                                <em class="fas fa-reply"></em> Refund</a></li>
                                            @endif
                                            @if($trnx->status == 'pending' || $trnx->status == 'onhold')
                                                @if($trnx->payment_method == 'bank' || $trnx->payment_method == 'manual')
                                                <li><a href="javascript:void(0)" onclick="approveTransaction({{$trnx->id}})" data-id="{{ $trnx->id }}">
                                                    <em class="far fa-check-square"></em>Approve</a></li>
                                                @endif
                                                @if($trnx->tnx_type != 'transfer')
                                                <li id="canceled"><a href="javascript:void(0)" onclick="cancelTransaction({{$trnx->id}})" class="tnx-action" data-type="canceled" data-id="{{ $trnx->id }}">
                                                    <em class="fas fa-ban"></em>Cancel</a></li>
                                                @endif
                                            @endif
                                            @if($trnx->status == 'canceled')
                                                @if( !empty($trnx->checked_by) && ($trnx->payment_method == 'bank' || $trnx->payment_method == 'manual'))
                                                <li><a href="javascript:void(0)" id="adjust_token" data-id="{{ $trnx->id }}">
                                                    <em class="far fa-check-square"></em>Approve</a></li>
                                                @endif
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>{{-- .data-item --}}
                        @endforeach
                    </tbody>
                </table>
                @else 
                    <div class="bg-light text-center rounded pdt-5x pdb-5x">
                        <p><em class="ti ti-server fs-24"></em><br>{{ ($is_page=='all') ? 'No transaction found!' : 'No '.$is_page.' transaction here!' }}</p>
                        <p><a class="btn btn-primary btn-auto" href="{{ route('admin.transactions') }}">View All Transactions</a></p>
                    </div>
                @endif

                @if ($pagi->hasPages())
                <div class="pagination-bar">
                    <div class="d-flex flex-wrap justify-content-between guttar-vr-20px guttar-20px">
                        <div class="fake-class">
                            <ul class="btn-grp guttar-10px pagination-btn">
                                @if($pagi->previousPageUrl())
                                <li><a href="{{ $pagi->previousPageUrl() }}" class="btn ucap btn-auto btn-sm btn-light-alt">Prev</a></li>
                                @endif 
                                @if($pagi->nextPageUrl())
                                <li><a href="{{ $pagi->nextPageUrl() }}" class="btn ucap btn-auto btn-sm btn-light-alt">Next</a></li>
                                @endif
                            </ul>
                        </div>
                        <div class="fake-class">
                            <div class="pagination-info guttar-10px justify-content-sm-end justify-content-mb-end">
                                <div class="pagination-info-text ucap">Page </div>
                                <div class="input-wrap w-80px">
                                    <select class="select select-xs select-bordered goto-page" data-dd-class="search-{{ ($pagi->lastPage() > 7) ? 'on' : 'off' }}">
                                        @for ($i = 1; $i <= $pagi->lastPage(); $i++)
                                        <option value="{{ $pagi->url($i) }}"{{ ($pagi->currentPage() ==$i) ? ' selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            <div class="pagination-info-text ucap">of {{ $pagi->lastPage() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>{{-- .card-innr --}}
        </div>{{-- .card --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection

@section('modals')
<div class="modal fade" id="addTnx">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <h3 class="popup-title">Manually Add Tokens</h3>
                <form action="{{ route('admin.ajax.transactions.add') }}" method="POST" class="validate-modern" id="add_token" autocomplete="off">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Tranx Type</label>
                                <div class="input-wrap">
                                    <select name="type" class="select select-block select-bordered" required>
                                        <option value="purchase">Purchase</option>
                                        <option value="bonus">Bonus</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label w-sm-60">
                                <label class="input-item-label">Tranx Date</label>
                                <div class="input-wrap">
                                    <input class="input-bordered date-picker" required="" type="text" name="tnx_date" value="{{ date('m/d/Y') }}">
                                    <span class="input-icon input-icon-right date-picker-icon"><em class="ti ti-calendar"></em></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Token Added To</label>
                                <div class="input-wrap">
                                    <select name="user" required="" class="select-block select-bordered" data-dd-class="search-on">
                                        @forelse($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @empty
                                        <option value="">No user found</option>
                                        @endif
                                    </select>
                                    <span class="input-note">Select account to add token.</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Token for Stage</label>
                                <div class="input-wrap">
                                    <select name="stage" class="select select-block select-bordered" required>
                                        @forelse($stages as $stage)
                                        <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                                        @empty
                                        <option value="">No active stage</option>
                                        @endif
                                    </select>
                                    <span class="input-note">Select Stage where from adjust tokens.</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Payment Gateway</label>
                                <div class="input-wrap">
                                    <select name="payment_method" class="select select-block select-bordered">
                                        @foreach($pmethods as $pmn)
                                        <option value="{{ $pmn->payment_method }}">{{ ucfirst($pmn->payment_method) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="input-note">Select method for this transaction.</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Payment Amount</label>
                                <div class="row flex-n guttar-10px">
                                    <div class="col-7">
                                        <div class="input-wrap">
                                            <input class="input-bordered" type="number" name="amount" placeholder="Optional">
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="input-wrap">
                                            <select name="currency" class="select select-block select-bordered">
                                                @foreach($pm_currency as $gt => $full)
                                                @if(token('purchase_'.$gt) == 1)
                                                <option value="{{ strtoupper($gt) }}"{{ base_currency()==$gt ? ' selected=""' : '' }}>{{ strtoupper($gt) }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <span class="input-note">Amount calculate based on stage if leave blank.</span>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Payment Address</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="text" name="wallet_address" placeholder="Optional">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Number of Token</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="number" name="total_tokens" max="{{ active_stage()->max_purchase }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label d-none d-sm-inline-block">&nbsp;</label>
                                <div class="input-wrap input-wrap-checkbox mt-sm-2">
                                    <input id="auto-bonus" class="input-checkbox input-checkbox-md" type="checkbox" name="bonus_calc">
                                    <label for="auto-bonus"><span>Bonus Adjusted from Stage</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Token</button>
                    <div class="gaps-3x"></div>
                    <div class="note note-plane note-light">
                        <em class="fas fa-info-circle"></em>
                        <p>If checked <strong>'Bonus Adjusted'</strong>, it will applied bonus based on selected stage (only for Purchase type).</p>
                    </div>
                </form>
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>
{{-- Modal End --}}
<script>
    var approveTransaction = (transId) => {
        Swal.fire({
        title: 'Are you sure?',
        text: "This action will approve this transaction",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                var data = {trans_id: transId}
                $.ajax({
                    url: "{{route('admin.ajax.transactions.approve')}}",
                    type: 'POST',
                    data: data,
                    cache: false,
                    dataType: 'json',

                    success: function (data) {
                        if (data.status == 1) {
                            Swal.fire({
                                title: 'Your work has been saved',
                                timer: 2000,
                                timerProgressBar: true,
                                icon: 'success',
                                onClose: () => {
                                    window.location.reload()
                                }
                               
                            })

                        }
                    },
                    error: function () {
                        // trumbowyg.addErrorOnModalField(
                        //     $('input[type=text]', $modal),
                        //     trumbowyg.lang.noembedError
                        // );
                    }
                });
            }
        })

    }

    var cancelTransaction = (transId) => {
        Swal.fire({
        title: 'Are you sure?',
        text: "This action will cancel this transaction",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                var data = {trans_id: transId}
                $.ajax({
                    url: "{{route('admin.ajax.transactions.cancel')}}",
                    type: 'POST',
                    data: data,
                    cache: false,
                    dataType: 'json',

                    success: function (data) {
                        if (data.status == 1) {
                            Swal.fire({
                                title: 'Your work has been saved',
                                timer: 2000,
                                timerProgressBar: true,
                                icon: 'success',
                                onClose: () => {
                                    window.location.reload()
                                }
                               
                            })

                        }
                    },
                    error: function () {
                        // trumbowyg.addErrorOnModalField(
                        //     $('input[type=text]', $modal),
                        //     trumbowyg.lang.noembedError
                        // );
                    }
                });
            }
        })

    }
</script>
@endsection