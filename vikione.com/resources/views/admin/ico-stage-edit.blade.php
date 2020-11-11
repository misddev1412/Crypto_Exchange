@extends('layouts.admin')
@section('title', 'ICO/STO Stage')

@section('content')
<div class="page-content">
    <div class="container">
        <div class="card content-area">
            <div class="card-innr">
                <div class="card-head d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">ICO/STO Stage Update</h4>
                    <div class="d-flex guttar-15px">
                        <div class="fake-class">
                            <span class="badge badge-lg badge-dim badge-lighter d-none d-md-inline-block">Sold: {{ number_format($ico->soldout, 2).' '. token_symbol() }}</span>
                        </div>
                        <div class="fake-class">
                            <form action="{{ route('admin.ajax.stages.active') }}" method="POST">
                                @csrf
                                <a href="javascript:void(0);" id="update_stage" data-type = "active_stage" data-id="{{$ico->id}}" class="btn btn-icon btn-sm btn-{{(get_setting('actived_stage') == $ico->id)?'danger disabled':'danger-alt'}}"><em class="fas fa-star"></em></a>
                                <input class="input-bordered" type="hidden" name="actived_stage" value="{{ $ico->id }}">
                            </form>
                        </div>
                        <div class="fake-class">
                            <a href="{{route('admin.stages')}}" class="btn btn-sm btn-auto btn-primary d-sm-inline-block d-none"><em class="fas fa-arrow-left"></em><span>Back</span></a>
                            <a href="{{route('admin.stages')}}" class="btn btn-icon btn-sm btn-primary d-sm-none"><em class="fas fa-arrow-left"></em></a>
                        </div>
                    </div>
                </div>
                <div class="gaps-1x"></div>
                <ul class="nav nav-tabs nav-tabs-line" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#ico_stage">Stage Details</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#ico_stage_price">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#ico_stage_bonus">Bonuses</a>
                    </li>
                </ul>{{-- .nav-tabs-line --}}
                <div class="tab-content" id="ico-details">
                    <div class="tab-pane fade show active" id="ico_stage">
                        <form action="{{ route('admin.ajax.stages.update') }}" class="_reload validate-modern" method="POST" id="ico_stage" autocomplete="off">
                            @csrf
                            <input type="hidden" name="ico_id" value="{{ $ico->id }}">
                            <div id="stageDetails" class="wide-max-md">
                                {{-- Stage Details Form --}}
                                <div class="input-item input-with-label">
                                    <label class="input-item-label">Stage Title/Name
                                        @if((date('Y-m-d H:i:s') >= $ico->start_date) && (date('Y-m-d H:i:s') <= $ico->end_date) && (get_setting('actived_stage') == $ico->id) && ($ico->status != 'paused'))
                                        <span class="ucap badge badge-success ml-2">Running</span>
                                        @elseif((date('Y-m-d H:i:s') >= $ico->start_date && date('Y-m-d H:i:s') <= $ico->end_date) && ($ico->status == 'paused'))
                                        <span class="ucap badge badge-purple ml-2">Paused</span>
                                        @elseif((date('Y-m-d H:i:s') >= $ico->start_date && date('Y-m-d H:i:s') <= $ico->end_date) && ($ico->status != 'paused'))
                                        <span class="ucap badge badge-secondary ml-2">Inactive</span>
                                        @elseif($ico->start_date > date('Y-m-d H:i:s') && date('Y-m-d H:i:s') < $ico->end_date)
                                        <span class="ucap badge badge-warning ml-2">Upcoming</span>
                                        @elseif(($ico->start_date > date('Y-m-d H:i:s')) && (date('Y-m-d H:i:s') < $ico->end_date))
                                        <span class="ucap badge badge-info ml-2">Completed</span>
                                        @else
                                        <span class="ucap badge badge-danger ml-2">Expired</span>
                                        @endif
                                    </label>
                                   <div class="input-wrap">
                                    <input class="input-bordered" type="text" name="name" value="{{ $ico->name }}" required>
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Total Token Issues</label>
                                           <div class="input-wrap">
                                            <input class="input-bordered" type="number" min="1" name="total_tokens" value="{{ $ico->total_tokens }}" required  >
                                            </div>
                                            <span class="input-note">Define how many tokens available for sale on stage.</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Base Token Price</label>
                                           <div class="input-wrap">
                                            <input class="input-bordered" type="number" min="0" name="base_price" value="{{ $ico->base_price }}" required>
                                            </div>
                                            <span class="input-note">Define your token rate. Usually $0.25 {{ base_currency(true) }} per token.</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Min and Max Per Transaction</label>
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <div class="relative">
                                                       <div class="input-wrap">
                                                        <input class="input-bordered" type="number" placeholder="Min" name="min_purchase" min="1" value="{{ $ico->min_purchase }}">
                                                   </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="relative">
                                                       <div class="input-wrap">
                                                        <input class="input-bordered" type="number" placeholder="Max" name="max_purchase" min="1" max="{{$ico->total_tokens}}" value="{{ $ico->max_purchase }}">
                                                    </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <span class="input-note">Purchase min or max amount of token per tranx.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="input-item input-with-label">
                                                    <label class="input-item-label">Soft Cap</label>
                                                   <div class="input-wrap">
                                                    <input class="input-bordered" type="number" name="soft_cap" value="{{ ($ico->soft_cap > 1 ? $ico->soft_cap : '') }}" max="{{$ico->total_tokens}}">
                                               </div>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="input-item input-with-label">
                                                    <label class="input-item-label">Hard Cap</label>
                                                   <div class="input-wrap">
                                                    <input class="input-bordered" type="number" name="hard_cap" value="{{ ($ico->hard_cap > 1 ? $ico->hard_cap : '') }}" max="{{$ico->total_tokens}}">
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-12 d-none">
                                                <div class="input-item input-with-label">
                                                    <label class="input-item-label">Display Token as</label>
                                                    <select class="select select-block select-bordered" name="display_mode">
                                                        <option {{ $ico->display_mode == 'normal' ? 'selected' : '' }} value="normal">Base -> Token</option>
                                                        <option {{ $ico->display_mode == 'reverse' ? 'selected' : '' }} value="reverse">Token -> Base</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-4 mt-1">
                                        <div class="sap"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Start Date</label>
                                            <div class="row guttar-15px align-items-center">
                                                <div class="col-7 col-sm-7">
                                                    <div class="input-wrap">
                                                        <input class="input-bordered date-picker" type="text" name="start_date" value="{{ stage_date($ico->start_date) }}"  required>
                                                        <span class="input-icon input-icon-right date-picker-icon"><em class="ti ti-calendar"></em></span>
                                                    </div>
                                                </div>
                                                <div class="col-5 col-sm-5">
                                                    <div class="input-wrap">
                                                        <input class="input-bordered time-picker" type="text" name="start_time" value="{{ stage_time($ico->start_date) }}" >
                                                        <span class="input-icon input-icon-right time-picker-icon"><em class="ti ti-alarm-clock"></em></span>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <span class="input-note">Start date/time for sale. Can't purchase before time.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">End Date</label>
                                            <div class="row guttar-15px align-items-center">
                                                <div class="col-7 col-sm-7">
                                                    <div class="input-wrap">
                                                        <input class="input-bordered custom-date-picker" type="text" name="end_date" value="{{ stage_date($ico->end_date) }}"  required>
                                                        <span class="input-icon input-icon-right date-picker-icon"><em class="ti ti-calendar"></em></span>
                                                    </div>
                                                </div>
                                                <div class="col-5 col-sm-5">
                                                   <div class="input-wrap">
                                                        <input class="input-bordered time-picker" type="text" name="end_time" value="{{ stage_time($ico->end_date, 'end') }}" >
                                                        <span class="input-icon input-icon-right time-picker-icon"><em class="ti ti-alarm-clock"></em></span>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <span class="input-note">Finish date/time for sale. Can't purchase after time.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="input-item input-with-label">
                                            <div class="row guttar-15px align-items-center">
                                                <div class="col-12">
                                                    <div class="input-wrap">
                                                        <input type="checkbox" class="input-switch" id="sale_pause" {{($ico->status != 'paused')?'checked ':''}}name="sale_pause">
                                                        <label for="sale_pause">Sales Running</label>
                                                    </div>
                                                    <span class="input-note">Disable this, if you want to stop sale temporary. Note: Contributor still able to calculate token.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="gaps-1-5x"></div>
                                <div class="d-flex">
                                    <button class="btn btn-primary save-disabled" type="submit" disabled="">Update Stage</button>
                                </div>
                                {{-- .form-wrap --}}
                            </div>
                        </form>
                    </div>{{-- .tab-pane --}}
                    <div class="tab-pane fade" id="ico_stage_price">
                        <form class="form-wrap w-xl-16x validate-modern" action="{{ route('admin.ajax.stages.meta.update') }}" method="POST" id="ico_stage_price" autocomplete="off">
                            @csrf
                            <input type="hidden" name="req_type" value="price_option">
                            <input type="hidden" name="ico_id" value="{{ $ico->id }}">
                            <div id="stagePrice">
                                @php
                                $pd = $prices;
                                @endphp
                                <div class="stage-tire-group">
                                    <div class="stage-tire-item">
                                        @for($i=1; $i <= 3; $i++)
                                        @php $tire = 'tire_'.$i; @endphp
                                        <div class="stage-tire-title">
                                            <div class="input-item pb-0">
                                                <input class="input-switch input-switch-left switch-toggle" data-switch="switch-to-priceTier0{{ $i }}" {{ $pd->$tire->status == 1 ? 'checked' : '' }}  type="checkbox" id="priceTier0{{ $i }}" name="ptire_{{ $i }}" value="1">
                                                <label for="priceTier0{{ $i }}"></label>
                                            </div>
                                            <span class="h5">Price Tier - 0{{ $i }}</span>
                                        </div>
                                        <div class="switch-content switch-to-priceTier0{{ $i }} wide-max-md">
                                            <div class="row">
                                                <div class="col-md">
                                                    <div class="input-item input-with-label">
                                                        <label class="input-item-label">Token Price</label>
                                                        <div class="input-wrap">
                                                            <input class="input-bordered" type="text" min="0" name="ptire_{{ $i }}_token_price" value="{{ $pd->$tire->price }}">
                                                            <span class="input-note">Base Price: <span>{{ $ico->base_price.' '.base_currency(true) }}</span></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md">
                                                    <div class="input-item input-with-label">
                                                        <label class="input-item-label">Min Purchase</label>
                                                        <div class="input-wrap">
                                                            <input class="input-bordered" type="number" name="ptire_{{ $i }}_min_purchase" min="0" value="{{ $pd->$tire->min_purchase }}">
                                                            <span class="input-note">Base Min: <span>{{ $ico->min_purchase }} {{ token('symbol') }}</span>. '0' to set as base.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md">
                                                    <div class="input-item input-with-label">
                                                        <label class="input-item-label">Start Date</label>
                                                        <div class="row guttar-15px">
                                                            <div class="col-7 col-sm-7">
                                                                <div class="input-wrap">
                                                                    <span class="input-icon input-icon-right date-picker-icon"><em class="ti ti-calendar"></em></span>
                                                                    <input class="input-bordered custom-date-picker" id="pdate_start" type="text" name="ptire_{{ $i }}_start_date" value="{{ stage_date($pd->$tire->start_date) }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-5 col-sm-5">
                                                                <div class="input-wrap">
                                                                    <input class="input-bordered time-picker" type="text" name="ptire_{{ $i }}_start_time" value="{{ stage_time($pd->$tire->start_date) }}">
                                                                    <span class="input-icon input-icon-right time-picker-icon"><em class="ti ti-alarm-clock"></em></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <span class="input-note">Start date/time for sale.</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md">
                                                    <div class="input-item input-with-label">
                                                        <label class="input-item-label">End Date</label>
                                                        <div class="row guttar-15px">
                                                            <div class="col-7 col-sm-7">
                                                                <div class="input-wrap">
                                                                    <span class="input-icon input-icon-right date-picker-icon"><em class="ti ti-calendar"></em></span>
                                                                    <input class="input-bordered custom-date-picker" type="text" name="ptire_{{ $i }}_end_date" value="{{ stage_date($pd->$tire->end_date) }}"  data-msg-greaterThan="End date is must be greater then start date">
                                                                </div>
                                                            </div>
                                                            <div class="col-5 col-sm-5">
                                                                <div class="input-wrap">
                                                                    <input class="input-bordered time-picker" type="text" name="ptire_{{ $i }}_end_time" value="{{ stage_time($bonuses->base->end_date, 'end') }}">
                                                                    <span class="input-icon input-icon-right time-picker-icon"><em class="ti ti-alarm-clock"></em></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <span class="input-note">Finish date/time for sale.</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endfor
                                    </div>
                                </div>{{-- .stage-tire-group --}}
                                <div class="gaps-1x"></div>
                                <div class="d-flex pdb-2-5x">
                                    <button class="btn btn-primary save-disabled" type="submit" disabled>Update Price</button>
                                </div>
                                <div class="sap"></div>
                                <div class="gaps-2x"></div>
                                <div class="notes">
                                    <ul>
                                       <li>^ Price will set lowest amount, if you multiple condition of price applied on same date.</li>
                                       <li>^ Stage date and time will set if not defined date and time in each pricing option.</li>
                                   </ul>
                               </div>
                           </div>
                       </form>
                   </div>{{-- .tab-pane --}}
                   <div class="tab-pane fade" id="ico_stage_bonus">
                    <form action="{{ route('admin.ajax.stages.meta.update') }}" class="validate-modern" method="POST" id="ico_stage_bonus" autocomplete="off">
                        @csrf
                        <input type="hidden" name="req_type" value="bonus_option">
                        <input type="hidden" name="ico_id" value="{{ $ico->id }}">
                        <div  id="stageBonus">
                            {{-- Stage Bonus Form --}}
                            <div class="stage-tire-group">
                                <div class="stage-tire-item wide-max-md">
                                    <div class="stage-tire-title">
                                        <span class="h5">Base Bonus</span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Bonus Amount</label>
                                                <div class="d-flex guttar-20px align-items-center">
                                                    <div class="w-120px">
                                                        <div class="input-wrap">
                                                            <input min="0" max="100" class="input-bordered" type="number" name="bb_amount" value="{{ $bonuses->base->amount }}">
                                                        
                                                            <span class="input-hint input-hint-lg"><span>%</span></span>
                                                        </div>
                                                    </div>
                                                    <div class="fake-class">
                                                        <span class="input-note pt-0">Usually 1-100%. <br>Extra tokens will add to contributor account.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Start Date</label>
                                                <div class="row guttar-15px">
                                                    <div class="col-7 col-sm-7">
                                                        <div class="input-wrap">
                                                           <input class="input-bordered custom-date-picker" type="text" name="bb_start_date" id="bbdate_end" value="{{ stage_date($bonuses->base->start_date) }}">
                                                           <span class="input-icon input-icon-right date-picker-icon"><em class="ti ti-calendar"></em></span>
                                                       </div>
                                                   </div>
                                                   <div class="col-5 col-sm-5">
                                                    <div class="input-wrap">
                                                        <input class="input-bordered time-picker" type="text" name="bb_start_time" value="{{ stage_time($bonuses->base->start_date) }}">
                                                        <span class="input-icon input-icon-right time-picker-icon"><em class="ti ti-alarm-clock"></em></span>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <span class="input-note">Start date/time for Bonus.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">End Date</label>
                                            <div class="row guttar-15px">
                                                <div class="col-7 col-sm-7">
                                                    <div class="input-wrap">
                                                       <input class="input-bordered custom-date-picker" type="text" name="bb_end_date" value="{{ stage_date($bonuses->base->end_date) }}"  data-msg-greaterThan="End date is must be greater then start date">
                                                       <span class="input-icon input-icon-right date-picker-icon"><em class="ti ti-calendar"></em></span>
                                                   </div>
                                               </div>
                                               <div class="col-5 col-sm-5">
                                                <div class="input-wrap">
                                                    <input class="input-bordered time-picker" type="text" name="bb_end_time" value="{{ stage_time($bonuses->base->end_date, 'end') }}">
                                                    <span class="input-icon input-icon-right time-picker-icon"><em class="ti ti-alarm-clock"></em></span>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <span class="input-note">Finish date/time for Bonus.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>{{-- .stage-tire --}}
                        <div class="stage-tire-item">
                            <div class="stage-tire-title">
                                <div class="input-item pb-1">
                                   <input class="input-switch switch-toggle" data-switch="switch-to-bonusAmount" type="checkbox" id="bonusAmount" {{ $bonuses->bonus_amount->status == 1 ? 'checked' : '' }} name="bonus_amount">
                                   <label for="bonusAmount"></label>
                               </div>
                               <div>
                                <span class="h5">Based on Tokens</span>
                                <p>You can specify bonus based on token sales amount.</p>
                            </div>
                        </div>{{-- .stage-timeline-item --}}
                        <div class="switch-content switch-to-bonusAmount wide-max-md">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row guttar-15px ">
                                        <div class="col-7 col-sm-6">
                                            <label class="input-item-label">Minimum Token</label>
                                        </div>
                                        <div class="col-5 col-sm-3">
                                            <label class="input-item-label">Bonus %</label>
                                        </div>
                                    </div>
                                    @php $ba = $bonuses->bonus_amount; @endphp
                                    @for($i=1; $i <=3; $i++)
                                    @php $ba_tire = 'tire_'.$i; @endphp
                                    <div class="pdb-2x">
                                        <div class="row guttar-15px guttar-vr-5px align-items-center">
                                            <div class="col-7 col-sm-6">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" type="number" min="1" max="{{ $ico->total_tokens }}" name="ba_token_{{ $i }}" value="{{ $ba->$ba_tire->token }}">
                                                    <span class="input-hint"><span>{{ token('symbol') }}</span></span>
                                                </div>
                                            </div>
                                            <div class="col-5 col-sm-3">
                                                <div class="input-wrap">
                                                   <input class="input-bordered" type="number" min="1" max="100" name="ba_amount_{{ $i }}" value="{{ $ba->$ba_tire->amount }}">
                                                   <span class="input-hint input-hint-lg"><span>%</span></span>
                                               </div>
                                           </div>
                                           <div class="col">
                                            <div class="input-sub-label">Tier {{ $i }}</div>
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>{{-- .stage-tire --}}
            </div>{{-- .stage-tire-group --}}
            <div class="gaps-1x"></div>
            <div class="d-flex pdb-2-5x">
                <button class="btn btn-primary save-disabled" disabled="" type="submit" disabled> Update Bonus</button>
            </div>
            <div class="sap"></div>
            <div class="gaps-2x"></div>
            <div class="notes">
               <ul>
                   <li>^ Bonus will set highest amount, if you multiple condition of bonus applied on same date.</li>
                   <li>^ Stage date and time will set if not defined date and time in each bonus option.</li>
               </ul>
           </div>
       </div>
   </form>
</div>{{-- .tab-pane --}}
</div>{{-- .tab-content --}}
</div>
</div>
</div>{{-- .container --}}
</div>{{-- .page-content --}}

@endsection