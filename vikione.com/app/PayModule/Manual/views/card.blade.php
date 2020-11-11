<div class="payment-card">
    <div class="payment-head">
        <div class="payment-logo">
            <img src="{{ asset('assets/images/pay-manual-admin.png') }}" alt="Manual">
        </div>
        <div class="payment-action">
            <a href="javascript:void(0)" class="toggle-tigger rotate"><em class="ti ti-more-alt"></em></a>
            <div class="toggle-class dropdown-content dropdown-content-top-left">
                <ul class="dropdown-list">
                    <li><a href="{{ route('admin.payments.setup.edit', $name) }}">Update</a></li>
                    <li><a class="quick-action" href="javascript:void(0)" data-name="manual">{{ $pmData->status == 'active' ? 'Disabled' : 'Enabled' }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="payment-body">
        <h5 class="payment-title">Manual Wallet Payment</h5>
        <p class="payment-text">Accept manual payment (ETH, BTC, LTC, etc) from contributors.</p>
        @if($pmData->secret->eth->address == null && $pmData->secret->btc->address == null && $pmData->secret->ltc->address == null)
        <div class="payment-status payment-status-connect">
            <a class="payment-status-icon" href="{{ route('admin.payments.setup.edit', $name) }}" ><em class="ti ti-plus"></em></a>
            <div class="payment-status-text">Connect your account</div>
        </div>
        @elseif($pmData->status == 'active')
        <div class="payment-status payment-status-connected">
            <span class="payment-status-icon"><em class="ti ti-check"></em></span>
            <div class="payment-status-text">Displayed on Purchase Tokens</div>
        </div>
        @else
        <div class="payment-status payment-status-disabled">
            <span class="payment-status-icon"><em class="ti ti-na"></em></span>
            <div class="payment-status-text">Currently disabled</div>
        </div>
        @endif
    </div>
    <div class="payment-footer">
        @if($pmData->secret->eth->address == null && $pmData->secret->btc->address == null && $pmData->secret->ltc->address == null)
        <span class="payment-not-conected">You have not connected yet.</span>
        @else
        <span class="payment-id-title">Active Currency</span>
        <span class="payment-id">{{ substr(($pmData->secret->eth->status == 'active' ? ' ETH,' : '').($pmData->secret->btc->status == 'active' ? ' BTC,' : '').($pmData->secret->ltc->status == 'active' ? ' LTC,' : ''), 0, -1 ) }} </span>
        @endif
    </div>
</div>