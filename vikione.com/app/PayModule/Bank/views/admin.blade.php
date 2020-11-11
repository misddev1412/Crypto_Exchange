<div class="page-content">
    <div class="container">
        <div class="card content-area">
            <div class="card-innr">
                <div class="card-head d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Bank Transfer Method</h4>
                    <a href="{{ route('admin.payments.setup') }}" class="btn btn-sm btn-auto btn-outline btn-primary d-sm-inline-block"><em class="fas fa-arrow-left"></em><span class="d-none d-sm-inline-block">Back</span></a>
                </div>
                <div class="gaps-1x"></div>
                <div class="card-text">
                    <p>Please enter your bank details for intentional or local bank transfer. All contributors received this details when they purchase your token in USD.</p>
                </div>
                <div class="gaps-2x"></div>
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('admin.ajax.payments.update') }}" method="POST" class="payment_methods_form validate-modern">
                            @csrf
                            <input type="hidden" name="req_type" value="bank">
                            <div class="row align-items-center">
                                <div class="col-sm col-md-3">
                                    <label class="card-title card-title-sm">Active or Deactive</label>
                                </div>
                                <div class="col-sm col-md-3">
                                    <div class="fake-class">
                                        <div class="input-wrap input-wrap-switch">
                                            <input class="input-switch" {{ $pmData->status == 'active' ? 'checked' : '' }} id="status" name="status" type="checkbox">
                                            <label for="status">
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
                                            <input class="input-bordered" value="{{ $pmData->title }}" type="text" name="title" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-item input-with-label">
                                        <label class="input-item-label">Description</label>
                                        <div class="input-wrap">
                                            <input class="input-bordered" value="{{ $pmData->details }}" placeholder="You can send paymeny direct to our wallets; We will manually verify" type="text" name="details">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="sap"></div>
                                </div>
                            </div>
                            <div class="bank-details pt-3">
                                <div class="row">
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Account Holder Name</label>
                                            <div class="input-wrap">
                                                <input class="input-bordered required" name="bank_account_name" value="{{ isset($pmData->secret->bank_account_name) ? $pmData->secret->bank_account_name : '' }}" type="text" placeholder="Enter Bank Account Name" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Account Number</label>
                                            <div class="input-wrap">
                                                <input class="input-bordered required number" name="bank_account_number" value="{{ isset($pmData->secret->bank_account_number) ? $pmData->secret->bank_account_number : '' }}" type="number" placeholder="Enter Bank Account Number" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Account Holder Address</label>
                                            <div class="input-wrap">
                                                <textarea name="bank_holder_address" id="bank_holder_address" cols="30" rows="1" class="h-100 input-bordered input-textarea" placeholder="Enter Account Holder Address">{{ isset($pmData->secret->bank_holder_address) ? $pmData->secret->bank_holder_address : '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Bank Name</label>
                                            <div class="input-wrap">
                                                <input class="input-bordered required" name="bank_name" value="{{ isset($pmData->secret->bank_name) ? $pmData->secret->bank_name : '' }}" type="text" placeholder="Enter Bank Name" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-8">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Bank Address</label>
                                            <div class="input-wrap">
                                                <textarea name="bank_address" id="bank_address" cols="30" rows="1" class="h-100 input-bordered input-textarea" placeholder="Enter Bank Address">{{ isset($pmData->secret->bank_address) ? $pmData->secret->bank_address : '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Routing Number</label>
                                            <div class="input-wrap">
                                                <input class="input-bordered number" name="routing_number" value="{{ isset($pmData->secret->routing_number) ? $pmData->secret->routing_number : '' }}" type="number" placeholder="Bank Routing Number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">IBAN Number</label>
                                            <div class="input-wrap">
                                                <input class="input-bordered" name="iban" value="{{ isset($pmData->secret->iban) ? $pmData->secret->iban : '' }}" type="text" placeholder="Bank IBAN Number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">SWIFT or BIC Code</label>
                                            <div class="input-wrap">
                                                <input class="input-bordered" name="swift_bic" value="{{ isset($pmData->secret->swift_bic) ? $pmData->secret->swift_bic : '' }}" type="text" placeholder="SWIFT/BIC Code">
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                            </div>{{-- .payment-wallet --}}
                            <div class="gaps-1x"></div>
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
