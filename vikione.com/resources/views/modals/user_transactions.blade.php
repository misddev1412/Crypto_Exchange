<div class="modal fade" id="user-transaction" tabindex="-1">
    <div class="modal-dialog modal-dialog-lg modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-lg">
                <h3 class="popup-title">All Transaction By <em class="ti ti-angle-right"></em> <small class="tnx-id">{{ set_id($user->id) }}</small></h3>
                <ul class="data-details-alt">
                    @forelse($transactions as $tnx)
                    <li class="text-dark row no-gutters justify-content-between">
                        <div class="col-12 col-lg order-lg-last"><small class="text-light data-details-date">{{ _date($tnx->tnx_time) }}</small></div>
                        <div class="col-md col-sm-6"><strong class="text-dark">{{ ucfirst($tnx->tnx_type) }}</strong></div>
                        <div class="col-md col-sm-6"><span class="text-dark">{{ get_stage($tnx->stage, 'name') }}</span></div>
                        <div class="col-md col-sm-6 order-sm-2 order-md-1"><span class="text-light"><a href="{{ route('admin.transactions.view', $tnx->id) }}" target="_blank">{{ $tnx->tnx_id }}</a></span></div>
                        <div class="col-md col-sm-6 order-sm-1 order-md-2"><strong class="text-dark">{{ (starts_with($tnx->total_tokens, '-') ? '' : '+').$tnx->total_tokens }}</strong></div>
                        <div class="col-md col-sm-6 order-sm-3"><span class="text-light small">{{ to_num($tnx->amount, 'max').' '.strtoupper($tnx->currency) }} <em class="fas fa-info-circle fs-11" data-toggle="tooltip" data-placement="bottom" title="1 {{ token('symbol') }} = {{ $tnx->currency_rate.' '.strtoupper($tnx->currency) }}"></em></span></div>
                    </li>
                    @empty
                    <li><div class="col-md col-sm-6"><strong class="text-dark">No approved transaction found!</strong></div></li>
                    @endforelse
                </ul>{{-- .data-details --}}
            </div>
        </div>
    </div>
</div>