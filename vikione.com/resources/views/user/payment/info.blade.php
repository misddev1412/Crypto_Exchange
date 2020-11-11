<div class="row guttar-vr-30px">
    <div class="col-xl-4 col-md-6 ">
        {{-- <a href="https://etherscan.io/address/0xc998ddb570ff0a561130cfbe4220bf5da7e44964" target="_blank"> --}}
            <div class="payment-card payment-disabled">
                <div class="payment-head">
                    <div class="payment-logo">
                        <img src="{{asset('assets/images/pay-manual-admin.png')}}" alt="Manual">
                    </div>
                    
                </div>
                <div class="payment-body">
                    <h5 class="payment-title">Manual Wallet Payment</h5>
                    <p class="payment-text">Payment by e-wallet</p>
                    <div class="payment-status payment-status-connected">
                        <span class="payment-status-icon"><em class="ti ti-check"></em></span>
                        <span class="payment-status-text">Selection</span>
                    </div>
                </div>
                {{-- <div class="payment-footer">
                    <span class="payment-id-title">Active Currency</span>
                    <span class="payment-id"> ETH </span>
                </div> --}}
            </div>
        {{-- </a> --}}
    </div>
    <div class="col-xl-4 col-md-6 payment-enable ">
        <div class="payment-card" onclick="formPaymentMethod('banking', '{{$tnxId}}')">
            <div class="payment-head">
                <div class="payment-logo">
                    <img src="{{asset('assets/images/pay-bank-admin.png')}}" alt="Bank">
                </div>
               
            </div>
            <div class="payment-body">
                <h5 class="payment-title">Bank Transfer</h5>
                <p class="payment-text">Accept payments via Bank Transfer.</p>
                <div class="payment-status payment-status-connected">
                    <span class="payment-status-icon"><em class="ti ti-check"></em></span>
                    <span class="payment-status-text">Selection</span>
                </div>
            </div>
          
        </div>
    </div>
    <div class="col-xl-4 col-md-6 payment-disabled ">
        <div class="payment-card">
            <div class="payment-head">
                <div class="payment-logo">
                    <img src="{{asset('assets/images/pay-paypal-admin.png')}}" alt="PayPal">
                </div>
                
            </div>
            <div class="payment-body">
                <h5 class="payment-title">PayPal Gateway</h5>
                <p class="payment-text">Payment by paypal system</p>
                <div class="payment-status payment-status-connect">
                    <a class="payment-status-icon">
                    <em class="ti ti-check"></em></a>
                    <span class="payment-not-conected">Selection</span>

                </div>
            </div>
            {{-- <div class="payment-footer">
            </div> --}}
        </div>
    </div>
</div>