@extends('layouts.admin')
@section('title', 'Referral Setting')

@section('content')
<div class="page-content">
  <div class="container">
    <div class="row">
      <div class="main-content col-lg-12">
        @include('vendor.notice')
        <div class="content-area card">
          <div class="card-innr">
            <div class="card-head">
              <h4 class="card-title">Referral Settings</h4>
            </div>
            <div class="card-text">
              <p>Manage your referral system to boost your token sales. Once enable referral system option, it will start tracking invitation link also user/contributor able to see invitation link on their profile. They can share any where to invite more people to join on your platform. You can specify how much bonus a user can get.</p>
              <p class="text-head"><strong>Note: To active referral system completly, you have set Show in Visibility by edit Referral page from <a href="{{ route('admin.pages.edit', ['slug'=>'referral']) }}">Manage > Page</a></strong></p>
            </div>
            <div class="gaps-2x"></div>
            <div class="card-text ico-setting setting-token-referral">
              <form action="{{ route('admin.ajax.settings.update') }}" method="POST" id="referral_setting_form" class="validate-modern">
                <div class="row">
                  <div class="col-lg-3 col-sm-6">
                    <div class="input-item input-with-label">
                      <label class="input-item-label">Referral System</label>
                      <div class="input-wrap input-wrap-switch">
                        <input class="input-switch switch-toggle" data-switch="switch-to-referral" name="referral_system" type="checkbox" {{ get_setting('referral_system')==1 ? 'checked ' : '' }}id="referral-system-enable">
                        <label for="referral-system-enable"><span>Disable</span><span class="over">Enable</span></label>
                      </div>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="switch-content switch-to-referral">

                      <h5 class="card-title-sm text-secondary">Invited User <small class="ucap text-primary">(who joined)</small></h5>
                      <div class="row">
                        <div class="col-lg-3 col-sm-6">
                          <div class="input-item input-with-label">
                            <label class="input-item-label">Referral Bonus Allowed</label>
                            <div class="input-wrap">
                              <select id="bonus_applicable" class="select select-block select-bordered " name="referral_allow_join">
                                <option {{ get_setting('referral_allow_join') == 'all_time' ? 'selected' : '' }} value="all_time">For All Transactions</option>
                                @foreach($general->steps_join as $step)
                                <option {{ (get_setting('referral_allow') == $step) ? 'selected ' : '' }}value="{{ $step }}">Max {{ $step }} Transaction</option>
                                @endforeach
                              </select>
                            </div>
                            <span class="input-note">Limit with transaction, how many times bonus will add into account for purchase.</span>
                          </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                          <div class="input-item input-with-label">
                            <label class="input-item-label">Offering Type</label>
                            <div class="input-wrap">
                              <select class="select select-block select-bordered" name="referral_calc_join">
                                <option {{ get_setting('referral_calc_join') == 'percent' ? 'selected ' : '' }}value="percent">Percentage</option>
                                <option {{ get_setting('referral_calc_join') == 'fixed' ? 'selected ' : '' }}value="fixed">Fixed/Flat</option>
                              </select>
                            </div>
                            <span class="input-note">Choose whether the referral bonus will calculated as percentage or fixed amount.</span>
                          </div>
                        </div>
                        <div class="col-sm-12 col-lg-3">
                          <div class="input-item input-with-label">
                            <label class="input-item-label">Bonus - Offer Amount</label>
                            <div class="input-wrap wide-15">
                              <input type="number" class="input-bordered" min="0" name="referral_bonus_join" value="{{ get_setting('referral_bonus_join') }}">
                              <span class="input-hint input-hint-lg"><span>&nbsp;&nbsp;</span></span>
                            </div>
                            <div class="input-note">Specify bonus amount for who joined.</div>
                          </div>
                        </div>
                        <div class="col-sm-12 col-lg-3">
                          <div class="input-item input-with-label">
                            <label class="input-item-label">Show Info to User</label>
                            <div class="input-wrap input-wrap-switch">
                              <input class="input-switch" name="referral_info_show" type="checkbox" {{ get_setting('referral_info_show')==1 ? 'checked ' : '' }}id="info-show-enable">
                              <label for="info-show-enable"><span>Hide</span><span class="over">Show</span></label>
                            </div>
                            <div class="input-note">Referral info show to user on signup page, so the user can see who refer him.</div>
                          </div>
                        </div>
                      </div>
                      <h5 class="card-title-sm text-secondary">Referral User <small class="ucap text-primary">(who referred)</small></h5>
                      <div class="row">
                        <div class="col-lg-3 col-sm-6">
                          <div class="input-item input-with-label">
                            <label class="input-item-label">Referral Bonus Allowed</label>
                            <div class="input-wrap">
                              <select id="bonus_applicable" class="select select-block select-bordered " name="referral_allow">
                                <option {{ get_setting('referral_allow') == 'all_time' ? 'selected' : '' }} value="all_time">For All Transactions</option>
                                @foreach($general->steps_refer as $step)
                                <option {{ (get_setting('referral_allow') == $step) ? 'selected ' : '' }}value="{{ $step }}">Max {{ to_num_token($step) }} Tokens</option>
                                @endforeach
                              </select>
                            </div>
                            <span class="input-note">Limit with referral bonus amount, how much bonus will add into account for invite someone.</span>
                          </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                          <div class="input-item input-with-label">
                            <label class="input-item-label">Offering Type</label>
                            <div class="input-wrap">
                              <select class="select select-block select-bordered" name="referral_calc">
                                <option {{ get_setting('referral_calc') == 'percent' ? 'selected ' : '' }}value="percent">Percentage</option>
                                <option {{ get_setting('referral_calc') == 'fixed' ? 'selected ' : '' }}value="fixed">Fixed/Flat</option>
                              </select>
                            </div>
                            <span class="input-note">Choose whether the referral bonus will calculated as percentage or fixed amount.</span>
                          </div>
                        </div>
                        <div class="col-sm-12 col-lg-3">
                          <div class="input-item input-with-label">
                            <label class="input-item-label">Bonus - Offer Amount</label>
                            <div class="input-wrap wide-15">
                              <input type="number" class="input-bordered" min="0" name="referral_bonus" value="{{ get_setting('referral_bonus') }}">
                              <span class="input-hint input-hint-lg"><span>&nbsp;&nbsp;</span></span>
                            </div>
                            <div class="input-note">Specify bonus amount for who referred.</div>
                          </div>
                        </div>
                      </div>
                      @if(!empty($advanced) && $advanced->valid > 0 && !empty($advanced->options))
                      <div class="sap sap-gap mt-3"></div>
                      <div class="row">
                        <div class="col-12">
                          <h5 class="card-title-sm text-secondary">Advanced Options</h5>
                          <div class="row">
                            @foreach($advanced->options as $opt)
                            <div class="col-lg-3 col-sm-6">
                              <div class="input-item input-with-label">
                                <label class="input-item-label">{{ $opt['title'] }} - Bonus Allowed</label>
                                <div class="input-wrap">
                                  <select class="select select-block select-bordered" name="{{ $advanced->keys }}[l{{ $opt['id'] }}][allow]">
                                    <option {{ (isset($opt['allow']) && $opt['allow'] == 'all_time') ? 'selected ' : '' }}value="all_time">No Limit / Always</option>
                                    @foreach($advanced->steps as $step)
                                    <option {{ (isset($opt['allow']) && $opt['allow'] == $step) ? 'selected ' : '' }}value="{{ $step }}">Upto {{ to_num_token($step) }} Tokens</option>
                                    @endforeach
                                  </select>
                                </div>
                                <span class="input-note">Limit with referral bonus amount.</span>
                              </div>
                            </div>
                            <div class="col-lg-3 col-sm-6" id="referral-level{{ $opt['id'] }}">
                              <div class="input-item input-with-label">
                                <label class="input-item-label">{{ $opt['title'] }} - Bonus Offer</label>
                                <div class="row guttar-10px">
                                  <div class="col-7">
                                    <div class="input-wrap">
                                      <select class="select select-block select-bordered" name="{{ $advanced->keys }}[l{{ $opt['id'] }}][type]">
                                        <option {{(isset($opt['type']) && $opt['type'] == 'percent') ? 'selected ' : '' }}value="percent">Percent</option>
                                        <option {{ (isset($opt['type']) && $opt['type'] == 'fixed') ? 'selected ' : '' }}value="fixed">Fixed</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-5">
                                    <div class="input-wrap">
                                      <input type="number" class="input-bordered" min="0" name="{{ $advanced->keys }}[l{{ $opt['id'] }}][amount]" value="{{ (isset($opt['amount']) ? $opt['amount'] : 0) }}">
                                      <span class="input-hint input-hint-lg"><span>&nbsp;&nbsp;</span></span>
                                    </div>
                                  </div>
                                </div>
                                <div class="input-note">Set '{{ $opt['title'] }}' bonus amount for each time.</div>
                              </div>
                            </div>
                            @endforeach
                          </div>
                        </div>
                      </div>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="gaps-1x"></div>
                <div class="d-flex">
                  @csrf
                  <input type="hidden" name="type" value="referral">
                  <button class="btn btn-primary save-disabled" type="submit" disabled><i class="ti ti-reload"></i><span>Update</span></button>
                </div>
                <div class="gaps-2x"></div>
                <div class="hint"><em><strong>Note:</strong> Bonus will automatically adjust after each successfull transaction. The token balance add into contributor account who referred (and/or who join).</em></div>
              </form>
            </div>
          </div>{{-- .card-innr --}}
        </div>{{-- .card --}}

      </div>{{-- .col --}}
    </div>{{-- .container --}}
  </div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection