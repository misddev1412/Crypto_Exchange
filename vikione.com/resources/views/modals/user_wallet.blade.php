@php
$user = auth()->user();
$wallets = token_wallet();

$notes = '<div class="note note-plane note-danger"><em class="fas fa-info-circle"></em><p>'.__("DO NOT USE your exchange wallet address OR if you don't have a private key of the your address. You WILL NOT receive your token and WILL LOSE YOUR FUNDS if you do.").'</p></div>';

$wallet_count = (!empty($wallets)) ? count($wallets) : 0;

$wallet_type_selection = ''; $is_single_wallet = false; $wallet_name = '';
$is_disable = ($user->walletType != NULL) ? ' disabled' : '';
$no_wallet = ($wallet_count >= 1) ? false : true;

if ($wallet_count >= 2) {
    foreach ($wallets as $wname => $wlabel) {
        $wallet_type_selection .= '<option '.($user->walletType == $wname ? 'selected ' : '').'value="'.$wname.'">'.$wlabel.'</option>';
    }
} elseif($wallet_count==1) {
    $wallet_type_selection .=  '<input type="hidden" name="wallet_name" value="'.strtolower(array_values($wallets)[0]).'">';
    $is_single_wallet = true;
    $wallet_name = array_values($wallets)[0].' ';
}

// dd($wallet, $wallets);
@endphp
<div class="modal fade" id="add-wallet" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <h4 class="popup-title">{{__('Wallet Address')}}</h4>
                <p>{!!__('In order to receive your :SYMBOL token, please select your wallet address and you have to put the address below input box. You will receive :SYMBOL token to this address after the token sale end.', ['symbol' => '<strong>'.token_symbol().'</strong>' ]) !!}</p>
                <div class="gaps-1x"></div>
                @if(!$no_wallet)
                    @if(has_wallet())
                    <ul class="nav nav-tabs"  role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#wallet">{{__('Current Wallet')}}</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#request">{{__('Request for change')}}</a></li>
                    </ul>
                    @endif
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="wallet">
                            <form class="validate-modern _reload" action="{{ route('user.ajax.account.update') }}" method="POST" id="nio-user-wallet-update" autocomplete="off">
                                @csrf
                                <input type="hidden" name="action_type" value="wallet">
                                @if ($is_single_wallet==true)
                                {!! $wallet_type_selection !!}
                                @else
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-item input-with-label">
                                            <label for="swalllet" class="input-item-label">{{ (!has_wallet() ? __('Select Wallet') : __('Wallet Type')) }}</label>
                                            <select class="select-bordered select select-block" name="wallet_name" id="swalllet"{{ $is_disable }}>
                                                {!! $wallet_type_selection !!}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            <div class="input-item input-with-label">
                                <label for="token-address" class="input-item-label">{{ (!has_wallet() ? __('Enter your :Name wallet address', ['name' => $wallet_name]) : __(':Name Wallet Address for receiving token', ['name' => $wallet_name]) ) }}</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="text" id="token-address" name="wallet_address" value="{{ $user->walletAddress }}"{{ $is_disable}} required>
                                </div>
                                <span class="input-note">{{__('Note:')}} {{ get_setting('token_wallet_note') }}</span>
                            </div>{{-- .input-item --}}
                            @if(!has_wallet())
                            {!! $notes !!}
                            <div class="gaps-3x"></div>
                            <div class="d-sm-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-primary">{{__('Add Wallet')}}</button>
                            </div>
                            @endif
                        </form>{{-- form --}}
                    </div>
                    @if(has_wallet())
                    <div class="tab-pane fade" id="request">
                        <form class="validate-modern _reload" action="{{ route('user.ajax.account.update') }}" method="POST" id="nio-user-wallet-request" autocomplete="off">
                            @csrf
                            <input type="hidden" name="action_type" value="wallet_request">
                            @if ($is_single_wallet==true)
                            {!! $wallet_type_selection !!}
                            @else
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-item input-with-label">
                                        <label for="swallletr" class="input-item-label">{{ (!has_wallet() ? __('Select Wallet') : __('Wallet Type')) }}</label>
                                        <select class="select-bordered select select-block" name="wallet_name" id="swallletr">
                                            {!! $wallet_type_selection !!}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="input-item input-with-label">
                                <label for="token-address2" class="input-item-label">{{ __('Enter your new :Name wallet address', ['name' => $wallet_name]) }}</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="text" id="token-address2" name="wallet_address" value="" required>
                                </div>
                                <span class="input-note">{{__('Note:')}} {{ get_setting('token_wallet_note') }}</span>
                            </div>{{-- .input-item --}}
                            {!! $notes !!}
                            <div class="gaps-3x"></div>
                            <div class="d-sm-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-primary">{{__('Request for Update Wallet')}}</button>
                            </div>
                        </form>{{-- form --}}
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>{{-- .modal-content --}}
</div>{{-- .modal-dialog --}}
</div>
{{-- Modal End --}}
<script type="text/javascript">
    (function($) {
        var $nio_user_wallet = $('#nio-user-wallet-update, #nio-user-wallet-request');
        if ($nio_user_wallet.length > 0) { ajax_form_submit($nio_user_wallet, true, 'ti ti-alert', true); }
    })(jQuery);
</script>