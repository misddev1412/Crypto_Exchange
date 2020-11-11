<div class="modal fade" id="transaction-details" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            @if($transaction)
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            @endif
            <div class="popup-body">
                <div class="content-area popup-content">
                    @include('layouts.token-details', ['transaction' => $transaction, 'details' => true])
                </div>
            </div>
        </div>
    </div>
</div>