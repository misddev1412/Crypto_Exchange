<div class="payment-card">
    <div class="payment-head">
        <div class="payment-logo">
            <img src="{{ asset('assets/images/pay-bank-admin.png') }}" alt="Bank">
        </div>
        <div class="payment-action">
            <a href="#" class="toggle-tigger rotate"><em class="ti ti-more-alt"></em></a>
            <div class="toggle-class dropdown-content dropdown-content-top-left">
                <ul class="dropdown-list">
                    <li><a href="{{ route('admin.payments.setup.edit', $name) }}">Update</a></li>
                    <li><a class="quick-action" href="javascript:void(0)" data-name="bank">{{ $pmData->status == 'active' ? 'Disabled' : 'Enabled' }}</a></li>
                </ul>
            </div>
        </div>
    </div>{{-- .payment-head --}}
    <div class="payment-body">
        <h5 class="payment-title">Bank Transfer</h5>
        <p class="payment-text">Accept payments via Bank Transfer from contributors.</p>
        @if($pmData->secret->bank_account_number == null)
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
    </div>{{-- .payment-body --}}
    <div class="payment-footer">
        @if($pmData->secret->bank_account_number == null)
        <span class="payment-not-conected">You have not connected yet.</span>
        @else
        <span class="payment-id-title">Bank Account </span>
        <span class="payment-id">{{ $pmData->secret->bank_account_number }} </span>
        @endif
    </div>{{-- .payment-footer --}}
</div>