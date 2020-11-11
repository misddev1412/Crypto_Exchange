@extends('layouts.admin')
@section('title', 'ICO/STO Setting')
@php
$wallet_opt = field_value_text('token_wallet_opt' , 'wallet_opt');
is_array($wallet_opt) ? true : $wallet_opt = array();
$custom = field_value_text('token_wallet_custom');
is_array($custom) ? true : $custom = array();
@endphp

@section('content')
<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="main-content col-lg-12">
                @include('vendor.notice')
                <div class="content-area card">
                    <div class="card-innr">
                        <div class="card-head">
                            <h4 class="card-title">ICO/STO Settings </h4>
                        </div>
                        <div class="gaps-1x"></div>
                        <div class="card-text ico-setting setting-token-details">
                            <h3 class="card-title-md text-primary">ICO/STO Token Details</h3>
                            <form action="{{ route('admin.ajax.stages.settings.update') }}" method="POST" id="stage_setting_details_form" class="validate-modern">
                                @csrf
                                <input type="hidden" name="req_type" value="token_details">
                                <div class="row">
                                    <div class="col-xl-3 col-sm-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Token Name</label>
                                            <div class="input-wrap">
                                                <input class="input-bordered" required type="text" name="token_name" value="{{ token('name') }}" minlength="3">
                                            </div>
                                            <span class="input-note">Enter name of token without spaces. Lower and uppercase can be used.</span>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Token Symbol</label>
                                            <div class="input-wrap">
                                                <input class="input-bordered" required type="text" name="token_symbol" value="{{ token('symbol') }}" minlength="2">
                                            </div>
                                            <span class="input-note">Usually 3-4 Letters like ETH, BTC, WISH etc.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-3 col-sm-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Decimal Minimum</label>
                                            <div class="input-wrap">
                                                <input class="input-bordered" type="number" name="token_decimal_min" value="{{ token('decimal_min') }}" min="2" max="10">
                                            </div>
                                            <span class="input-note">Minimum number of decimal point for calculation. 2-10 are accepted.</span>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Decimal Maximum</label>
                                            <div class="input-wrap">
                                                <input class="input-bordered" type="number" name="token_decimal_max" value="{{ token('decimal_max') }}" min="6" max="18">
                                            </div>
                                            <span class="input-note">Maximum number of decimal point for calculation. 6-18 are accepted.</span>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Decimal Display</label>
                                            <div class="input-wrap">
                                                <input class="input-bordered" type="number" name="token_decimal_show" value="{{ token('decimal_show') ? token('decimal_show') : 0 }}" min="0" max="8">
                                            </div>
                                            <span class="input-note">The number of decimal point apply to show number in User/Admin Card balance.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="gaps-1x"></div>
                                <div class="d-flex">
                                    <button class="btn btn-primary save-disabled" type="submit" disabled><i class="ti ti-reload"></i><span>Update</span></button>
                                </div>
                            </form>
                        </div>
                        <div class="sap sap-gap"></div>
                        <div class="card-text ico-setting setting-token-purchase">
                            <h4 class="card-title-md text-primary">Purchase & Addtional Setting</h4>
                            <form action="{{ route('admin.ajax.stages.settings.update') }}" method="POST" id="stage_setting_purchase_form" class="validate-modern">
                                @csrf
                                <input type="hidden" name="req_type" value="token_purchase">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Default Selection</label>
                                            <div class="input-wrap">
                                                <select class="select select-block select-bordered active_method" name="token_default_method">
                                                    @foreach($pm_gateways as $pmg => $pmval)
                                                    @if(get_setting('pmc_active_'.$pmg) == 1)
                                                    <option {{ token('default_method') == strtoupper($pmg) ? 'selected ' : '' }}value="{{ strtoupper($pmg) }}">{{ $pmval.(($pmg==base_currency()) ? ' (Based)' : '') }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Token Price Show in</label>
                                            <div class="input-wrap">
                                                <select class="select select-block select-bordered" name="token_default_in_userpanel">
                                                    @foreach($pm_gateways as $pmg => $pmval)
                                                    @if(get_setting('pmc_active_'.$pmg) == 1 && base_currency() != $pmg)
                                                    <option {{ token('default_in_userpanel') == strtoupper($pmg) ? 'selected ' : '' }}value="{{ strtoupper($pmg) }}"> {{ base_currency(true) }} -> {{ strtoupper($pmg) }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Per Token Price</label>
                                            <div class="input-wrap input-wrap-switch">
                                                <input class="input-switch" name="token_price_show" type="checkbox" {{ token('price_show') == 1 ? 'checked' : '' }} id="per-token-price">
                                                <label for="per-token-price">Show</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">KYC Before Purchase</label>
                                            <div class="input-wrap input-wrap-switch">
                                                <input class="input-switch" name="token_before_kyc" type="checkbox" {{ token('before_kyc') == 1 ? 'checked' : '' }} id="kyc-before-buy">
                                                <label for="kyc-before-buy">Enable</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="input-item-label">Purchase With</label>
                                        <ul class="d-flex flex-wrap checkbox-list checkbox-list-c5">
                                            @foreach($pm_gateways as $pmg => $pmval)
                                            @if(get_setting('pmc_active_'.$pmg) == 1)
                                            <li>
                                                <div class="input-item text-left">
                                                    <div class="input-wrap">
                                                        <input class="input-checkbox input-checkbox-sm all_methods" name="token_purchase_{{ $pmg }}" id="pw-{{ $pmg }}" {{ (token('purchase_'.$pmg) == 1) ? 'checked ' : ' '}} {{token('default_method') == strtoupper($pmg) ? 'disabled ' : ' ' }}  type="checkbox">
                                                        <label for="pw-{{ $pmg }}">{{ $pmval .' ('.strtoupper($pmg).')'}}</label>
                                                    </div>
                                                </div>
                                            </li>
                                            @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="gaps-2x"></div>
                                <h5 class="card-title-sm text-secondary">Progress Bar Setting</h5>
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Raised Amount Show in</label>
                                            <div class="input-wrap">
                                                <select class="select select-block select-bordered" name="token_sales_raised">
                                                    <option {{ token('sales_raised') == 'token' ? 'selected ' : '' }}value="token">Token Amount</option>
                                                    @foreach($pm_gateways as $pmg => $pmval)
                                                    @if(get_setting('pmc_active_'.$pmg) == 1)
                                                    <option {{ token('sales_raised') == $pmg ? 'selected ' : '' }}value="{{ $pmg }}">{{ $pmval.(($pmg==base_currency()) ? ' (Based)' : '') }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Total Amount Show in</label>
                                            <div class="input-wrap">
                                                <select class="select select-block select-bordered" name="token_sales_total">
                                                    <option {{ token('sales_total') == 'token' ? 'selected ' : '' }}value="token">Token Amount</option>
                                                    @foreach($pm_gateways as $pmg => $pmval)
                                                    @if(get_setting('pmc_active_'.$pmg) == 1)
                                                    <option {{ token('sales_total') == $pmg ? 'selected ' : '' }}value="{{ $pmg }}">{{ $pmval.(($pmg==base_currency()) ? ' (Based)' : '') }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Soft/HardCap Show in</label>
                                            <div class="input-wrap">
                                                <select class="select select-block select-bordered" name="token_sales_cap">
                                                    <option {{ token('sales_cap') == 'token' ? 'selected ' : '' }}value="token">Token Amount</option>
                                                    @foreach($pm_gateways as $pmg => $pmval)
                                                    @if(get_setting('pmc_active_'.$pmg) == 1)
                                                    <option {{ token('sales_cap') == $pmg ? 'selected ' : '' }}value="{{ $pmg }}">{{ $pmval.(($pmg==base_currency()) ? ' (Based)' : '') }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="gaps-1x"></div>
                                <div class="d-flex">
                                    <button class="btn btn-primary save-disabled" type="submit" disabled><i class="ti ti-reload"></i><span>Update</span></button>
                                </div>
                            </form>
                        </div>
                        <div class="sap sap-gap"></div>
                        <div class="card-text ico-setting setting-ico-userpanel">
                            <h4 class="card-title-md text-primary">User Panel Settings</h4>
                            <p>Manage your User/Investor panel setting for your application.</p>
                            <div class="gaps-1x"></div>
                            <form action="{{ route('admin.ajax.stages.settings.update') }}" method="POST" id="upanel_setting_form" class="validate-modern">
                                @csrf
                                <input type="hidden" name="req_type" value="user_panel">
                                <h5 class="card-title-sm text-secondary">User Dashboard</h5>
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Display Contribution In</label>
                                            <div class="row guttar-15px">
                                                <div class="col-6">
                                                    <div class="input-wrap">
                                                        <select class="select select-block select-bordered" name="user_in_cur1">
                                                            @foreach($pm_gateways as $cur => $name)
                                                            @if(get_setting('pmc_active_'.$cur) == 1 && $cur!=base_currency())
                                                            <option {{ gws('user_in_cur1') == $cur ? 'selected ' : '' }}value="{{ $cur }}">{{ strtoupper($cur) }}</option>
                                                            @endif
                                                            @endforeach
                                                            <option {{ gws('user_in_cur1') == 'hide' ? 'selected ' : '' }}value="hide">Hide</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-wrap">
                                                        <select class="select select-block select-bordered" name="user_in_cur2">
                                                            @foreach($pm_gateways as $cur => $name)
                                                            @if(get_setting('pmc_active_'.$cur) == 1 && $cur!=base_currency())
                                                            <option {{ gws('user_in_cur2') == $cur ? 'selected ' : '' }}value="{{ $cur }}">{{ strtoupper($cur) }}</option>
                                                            @endif
                                                            @endforeach
                                                            <option {{ gws('user_in_cur2') == 'hide' ? 'selected ' : '' }}value="hide">Hide</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="input-note">Select two currencies which will show on balance card for <strong>'Contribution in'</strong>.</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">My Token Page</label>
                                            <div class="input-wrap input-wrap-switch">
                                                <input class="input-switch" name="user_mytoken_page" type="checkbox" {{ gws('user_mytoken_page') == 1 ? 'checked' : '' }} id="show-mytoken-page">
                                                <label for="show-mytoken-page">Enable</label>
                                            </div>
                                            <span class="input-note">Whether enable or disable the <strong>'My Token'</strong> page from User Panel.</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Stage wise Overview</label>
                                            <div class="input-wrap input-wrap-switch">
                                                <input class="input-switch" name="user_mytoken_stage" type="checkbox" {{ gws('user_mytoken_stage') == 1 ? 'checked' : '' }} id="show-stage-overview">
                                                <label for="show-stage-overview">Show</label>
                                            </div>
                                            <span class="input-note">Whether show or hide the stage wise purchase overview on <strong>'My Token'</strong> page.</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="gaps-2x"></div>
                                <h5 class="card-title-sm text-secondary">Receiving Wallet for User Profile</h5>
                                <p class="wide-lg">You may need your user/investor wallet address so you can send token/smart contract to them. You can specify one or multiple or define your own name to ask your user/investor to provide address. If they provide then you can get from each user details.</p>
                                <div class="gaps-1x"></div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Supported Wallet</label>
                                            <div class="input-wrap">
                                                <select  name="token_wallet_opt[]" class="select select-block select-bordered" value="" data-placeholder="Select Options" multiple="multiple">
                                                    @foreach($supported_wallets as $name => $wallet)
                                                    <option {{in_array($name, $wallet_opt )? 'selected' : ''}} value="{{ $name }}">{{ $wallet }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="input-note">Choose one or multiple wallet name.</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Custom Wallet</label>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="input-wrap">
                                                        <input class="input-bordered" placeholder="wallet-name" type="text" name = "token_wallet_custom[]" value="{{ (!empty($custom['cw_name']) ? $custom['cw_name'] : '') }}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-wrap">
                                                        <input class="input-bordered" placeholder="Wallet Label" type="text" name="token_wallet_custom[]" value="{{ (!empty($custom['cw_text']) ? $custom['cw_text'] : '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="input-note">You can specify any custom wallet name.</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Note for Wallet</label>
                                            <div class="input-wrap">
                                                <input class="input-bordered" type="text" name="token_wallet_note" value="{{ get_setting('token_wallet_note')}}">
                                            </div>
                                            <span class="input-note">The note will show under the wallet address input field.</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-lg-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Before Purchase Alert</label>
                                            <div class="input-wrap input-wrap-switch">
                                                <input class="input-switch" name="token_wallet_req" type="checkbox" {{ get_setting('token_wallet_req')==1 ? 'checked ' : '' }}id="before-purchase-alert">
                                                <label for="before-purchase-alert"><span>Hide</span><span class="over">Show</span></label>
                                            </div>
                                            <div class="input-note">Promote 'enter wallet address before buy' on buy token page.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="gaps-1x"></div>
                                <div class="d-flex">
                                    <button class="btn btn-primary save-disabled" type="submit" disabled><i class="ti ti-reload"></i><span>Update</span></button>
                                </div>
                            </form>
                        </div>
                        {{-- @dd($modules) --}}
                        @if(isset($modules) && !empty($modules))
                        @foreach($modules as $opt)
                        @if(!empty($opt->view))
                            <div class="sap sap-gap"></div>
                            <div class="card-text ico-setting setting-ico-userpanel">
                                @includeIf($opt->view, $opt->variables)
                            </div>
                        @endif
                        @endforeach
                        @endif
                    </div>{{-- .card-innr --}}
                </div>{{-- .card --}}

            </div>{{-- .col --}}
        </div>{{-- .container --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection
