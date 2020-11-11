<div class="page-content">
    <div class="container">
        <div class="card content-area">
            <div class="card-innr">
                <div class="card-head d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Manual Payment Methods</h4>
                    <a href="{{ route('admin.payments.setup') }}" class="btn btn-sm btn-auto btn-outline btn-primary d-sm-inline-block"><em class="fas fa-arrow-left"></em><span class="d-none d-sm-inline-block">Back</span></a>
                </div>
                <div class="gaps-1x"></div>
                <div class="card-text">
                    <p>All contributors allow to send their payment for token purchase. So double check the address before entering it and be sure you have access of these wallet. You can use all of them or individually by enable each wallet.</p>
                </div>
                <div class="gaps-2x"></div>
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('admin.ajax.payments.update') }}" method="POST" class="payment_methods_form validate-modern">
                            @csrf
                            <input type="hidden" name="req_type" value="manual">
                            <div class="row align-items-center">
                                <div class="col-sm col-md-3">
                                    <label class="card-title card-title-sm">Active or Deactive</label>
                                </div>
                                <div class="col-sm col-md-3">
                                    <div class="fake-class">
                                        <div class="input-wrap input-wrap-switch">
                                            <input class="input-switch" {{ $pmData->status == 'active' ? 'checked' : '' }} id="mnl_status" name="mnl_status" type="checkbox">
                                            <label for="mnl_status">
                                                <span class="over">Inactive</span><span>Active Gateway</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="gaps-1x"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-item input-with-label">
                                        <label class="input-item-label">Method Title</label>
                                        <div class="input-wrap">
                                            <input class="input-bordered" value="{{ $pmData->title }}" type="text" name="mnl_title" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-item input-with-label">
                                        <label class="input-item-label">Description</label>
                                        <div class="input-wrap">
                                            <input class="input-bordered" value="{{ $pmData->details }}" placeholder="You can send paymeny direct to our wallets; We will manually verify" type="text" name="mnl_details">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h5 class="card-title card-title-sm pdt-1x pdb-1x text-primary">Wallets Address</h5>
                            <p>Enter your crypto public wallet address for received payment. It will display to your contributor when they going to purchase token.</p>
                            @foreach ($currencies as $each_cur)
                            <div class="fake-class">
                                <div class="payment-wallet-head">
                                    <div class="input-item">
                                        <div class="input-wrap">
                                            <input class="input-switch switch-toggle" data-switch="switch-to-wallet-{{ $each_cur }}" type="checkbox" {{ (isset($pmData->secret->$each_cur->status) ? ($pmData->secret->$each_cur->status == 'active' ? 'checked' : '') : '' ) }} id="wallet-{{ $each_cur }}" name="{{ $each_cur }}-status" value="active">
                                            <label for="wallet-{{ $each_cur }}"></label>
                                        </div>
                                    </div>
                                    <div class="input-item flex-grow-1">
                                        <a href="javascript:void(0)" class="switch-toggle-link" data-switch="switch-to-wallet-{{ $each_cur }}"></a>
                                        <h5 class="payment-wallet-title">{{ short_to_full($each_cur) }} Wallet</h5>
                                        <span class="payment-wallet-des">Public {{ strtoupper($each_cur) }} address to get payment</span>
                                    </div>
                                </div>{{-- .payment-wallet --}}
                                <div class="switch-content switch-to-wallet-{{ $each_cur }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-item input-with-label wide-max-sm">
                                                <label class="input-item-label">{{ strtoupper($each_cur) }} Wallet Address</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" placeholder="Enter your wallet address; be sure you have access of this." type="text" name="{{ $each_cur }}[address]" value="{{ (isset($pmData->secret->$each_cur->address) ? $pmData->secret->$each_cur->address : '' ) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 col-lg-2">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Confirm Number</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" min="0" max="15" type="number" name="{{ $each_cur }}[num]" value="{{ (isset($pmData->secret->$each_cur->num) ? $pmData->secret->$each_cur->num : 3 ) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 col-lg-2">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Payment Address</label>
                                                <div class="input-wrap">
                                                    <select class="select select-block select-bordered" name="{{ $each_cur }}[req]" >
                                                        <option{{ (isset($pmData->secret->$each_cur->req) && $pmData->secret->$each_cur->req=='yes') ? ' selected ' : '' }} value="yes">Required</option>
                                                        <option{{ (isset($pmData->secret->$each_cur->req) && $pmData->secret->$each_cur->req=='no') ? ' selected ' : '' }} value="no">Optional</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($each_cur=='eth') 
                                    <div class="row">
                                        <div class="col-6 col-md-3">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Gas Limit</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" placeholder="Optional" type="text" name="{{ $each_cur }}[limit]" value="{{ (isset($pmData->secret->$each_cur->limit) ? $pmData->secret->$each_cur->limit : '' ) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Gas price</label>
                                                <div class="input-wrap">
                                                    <input class="input-bordered" placeholder="Optional" type="text" value="{{ (isset($pmData->secret->$each_cur->price) ? $pmData->secret->$each_cur->price : '' ) }}" name="{{ $each_cur }}[price]">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @if (!$loop->last)
                                    <div class="sap"></div>
                                @endif                                
                            </div>
                            @endforeach
                            <div class="gaps-2x"></div>
                            <div class="d-flex pb-1">
                                <button class="btn btn-md btn-primary save-disabled" disabled type="submit">UPDATE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>{{-- .container --}}
</div>
