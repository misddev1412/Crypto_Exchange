@extends('layouts.admin')
@section('title', 'Affiliate Setting')

@section('content')
<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="main-content col-lg-12">
                @include('vendor.notice')
                <div class="content-area card">
                    <div class="card-innr">
                        <div class="card-head">
                            <h4 class="card-title">Affiliate Settings</h4>
                        </div>
                        <div class="gaps-2x"></div>
                        <div class="card-text ico-setting setting-token-point">
                            <form action="{{ route('admin.ajax.settings.update') }}" method="POST"
                                id="affiliate_setting_form" class="validate-modern">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Rank</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Point</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Direct</label>
                                        </div>
                                    </div>

                                    <div class="col-lg-2">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Indirect</label>
                                        </div>
                                    </div>

                                    <div class="col-lg-2">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Exchange</label>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                        <div class="col-lg-3">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Normal</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="affiliate_normal_point"
                                                        placeholder="0"
                                                        value="{{ get_setting('affiliate_normal_point') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-2">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="affiliate_normal_direct"
                                                        placeholder="0"
                                                        value="{{ get_setting('affiliate_normal_direct') }}">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-2">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="affiliate_normal_indirect"
                                                        placeholder="0"
                                                        value="{{ get_setting('affiliate_normal_indirect') }}">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-2">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="affiliate_normal_exchange"
                                                        placeholder="0"
                                                        value="{{ get_setting('affiliate_normal_exchange') }}">
                                                </div>
                                            </div>
                                        </div>


                                       
                                       
                                    </div>
                                <div class="row">
                                <div class="col-lg-3">
                                                <div class="input-item input-with-label">
                                                    <label class="input-item-label">Silver</label>
                                                </div>
                                            </div>
                                <div class="col-lg-2">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="affiliate_silver_point"
                                                        placeholder="0"
                                                        value="{{ get_setting('affiliate_silver_point') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-2">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="affiliate_silver_direct"
                                                        placeholder="0"
                                                        value="{{ get_setting('affiliate_silver_direct') }}">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-2">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="affiliate_silver_indirect"
                                                        placeholder="0"
                                                        value="{{ get_setting('affiliate_silver_indirect') }}">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-2">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="affiliate_silver_exchange"
                                                        placeholder="0"
                                                        value="{{ get_setting('affiliate_silver_exchange') }}">
                                                </div>
                                            </div>
                                        </div>
                                       
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3">
                                                <div class="input-item input-with-label">
                                                    <label class="input-item-label">Gold</label>
                                                </div>
                                            </div>
                                        <div class="col-lg-2">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="affiliate_gold_point"
                                                        placeholder="0"
                                                        value="{{ get_setting('affiliate_gold_point') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-2">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="affiliate_gold_direct"
                                                        placeholder="0"
                                                        value="{{ get_setting('affiliate_gold_direct') }}">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-2">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="affiliate_gold_indirect"
                                                        placeholder="0"
                                                        value="{{ get_setting('affiliate_gold_indirect') }}">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-2">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="affiliate_gold_exchange"
                                                        placeholder="0"
                                                        value="{{ get_setting('affiliate_gold_exchange') }}">
                                                </div>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Platinum</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="affiliate_platinum_point"
                                                        placeholder="0"
                                                        value="{{ get_setting('affiliate_platinum_point') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-2">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="affiliate_platinum_direct"
                                                        placeholder="0"
                                                        value="{{ get_setting('affiliate_platinum_direct') }}">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-2">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="affiliate_platinum_indirect"
                                                        placeholder="0"
                                                        value="{{ get_setting('affiliate_platinum_indirect') }}">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-2">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="affiliate_platinum_exchange"
                                                        placeholder="0"
                                                        value="{{ get_setting('affiliate_platinum_exchange') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Diamond</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="affiliate_diamond_point"
                                                        placeholder="0"
                                                        value="{{ get_setting('affiliate_diamond_point') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-2">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="affiliate_diamond_direct"
                                                        placeholder="0"
                                                        value="{{ get_setting('affiliate_diamond_direct') }}">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-2">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="affiliate_diamond_indirect"
                                                        placeholder="0"
                                                        value="{{ get_setting('affiliate_diamond_indirect') }}">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-2">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="affiliate_diamond_exchange"
                                                        placeholder="0"
                                                        value="{{ get_setting('affiliate_diamond_exchange') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                               
                                <div class="gaps-1x"></div>
                                <div class="d-flex">
                                    @csrf
                                    <input type="hidden" name="type" value="affiliate">
                                    <button class="btn btn-primary save-disabled" type="submit" disabled><i
                                            class="ti ti-reload"></i><span>Update</span></button>
                                </div>
                            </form>
                        </div>
                    </div>{{-- .card-innr --}}
                </div>{{-- .card --}}
            </div>{{-- .col --}}
        </div>{{-- .container --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection
