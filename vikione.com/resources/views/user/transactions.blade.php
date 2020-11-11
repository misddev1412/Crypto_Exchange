@extends('layouts.user')
@section('title', __('User Transactions'))

@push('header')
<script type="text/javascript">
    var view_transaction_url = "{{ route('user.ajax.transactions.view') }}";
</script>
@endpush

@section('content')
@include('layouts.messages')
<div class="card content-area content-area-mh">
    <div class="card-innr">
        <div class="card-head">
            <h4 class="card-title">{{__('Transactions list')}}</h4>
            <div class="card-text">
                <p>To perform this transaction feature, you must be in KYCs</p>
            </div>
            <div class="gaps-2x"></div>
        </div>
        <div class="gaps-1x"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="float-right position-relative">
                    <a href="#" class="btn btn-sm btn-auto btn-primary" data-toggle="modal" data-target="#addTnx">
                        <em class="fas fa-plus-circle"></em><span>Send <span class="d-none d-sm-inline-block">ONE</span></span>
                    </a>
                    <a href="#" class="btn btn-light-alt btn-xs dt-filter-text btn-icon toggle-tigger" style="position: relative;"> <em class="ti ti-settings"></em> </a>
                    <div class="toggle-class toggle-datatable-filter dropdown-content dropdown-dt-filter-text dropdown-content-top-left text-left">
                        <ul class="dropdown-list dropdown-list-s2">
                            <li><h6 class="dropdown-title">{{ __('Types') }}</h6></li>
                            <li>
                                <input class="data-filter input-checkbox input-checkbox-sm" type="radio" name="tnx-type" id="type-all" checked value="">
                                <label for="type-all">{{ __('Any Type') }}</label>
                            </li>
                            <li>
                                <input class="data-filter input-checkbox input-checkbox-sm" type="radio" name="tnx-type" id="type-purchase" value="Purchase">
                                <label for="type-purchase">{{ __('Purchase') }}</label>
                            </li>
                            @foreach($has_trnxs as $name => $has)
                            @if($has==1)
                            <li>
                                <input class="data-filter input-checkbox input-checkbox-sm" type="radio" name="tnx-type" id="type-{{ $name }}" value="{{ ucfirst($name) }}">
                                <label for="type-{{ $name }}">{{ ucfirst($name) }}</label>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                        <ul class="dropdown-list dropdown-list-s2">
                            <li><h6 class="dropdown-title">{{ __('Status') }}</h6></li>
                            <li>
                                <input class="data-filter input-checkbox input-checkbox-sm" type="radio" name="tnx-status" id="status-all" checked value="">
                                <label for="status-all">{{ __('Show All') }}</label>
                            </li>
                            <li>
                                <input class="data-filter input-checkbox input-checkbox-sm" type="radio" name="tnx-status" id="status-approved" value="approved">
                                <label for="status-approved">{{ __('Approved') }}</label>
                            </li>
                            <li>
                                <input class="data-filter input-checkbox input-checkbox-sm" type="radio" name="tnx-status" value="pending" id="status-pending">
                                <label for="status-pending">{{ __('Pending') }}</label>
                            </li>
                            <li>
                                <input class="data-filter input-checkbox input-checkbox-sm" type="radio" name="tnx-status" value="canceled" id="status-canceled">
                                <label for="status-canceled">{{ __('Canceled') }}</label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <table class="data-table dt-filter-init user-tnx">
            <thead>
                <tr class="data-item data-head">
                    <th class="data-col tnx-status dt-tnxno">{{ __('Tranx NO') }}</th>
                    <th class="data-col dt-token">{{ __('Tokens') }}</th>
                    <th class="data-col dt-amount">{{ __('Amount') }}</th>
                    <th class="data-col dt-base-amount">{{ base_currency(true) }} {{ __('Amount') }}</th>
                    <th class="data-col dt-point">{{__('Point') }}</th>
                    <th class="data-col dt-account">{{ __('To') }}</th>
                    <th class="data-col dt-type tnx-type"><div class="dt-type-text">{{ __('Type') }}</div></th>
                    <th class="data-col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($trnxs as $trnx)
                @php 
                    $text_danger = ( $trnx->tnx_type=='refund' || ($trnx->tnx_type=='transfer' && $trnx->extra=='sent') ) ? ' text-danger' : '';
                @endphp
                <tr class="data-item tnx-item-{{ $trnx->id }}">
                    <td class="data-col dt-tnxno">
                        <div class="d-flex align-items-center">
                            <div class="data-state data-state-{{ str_replace(['progress','canceled'], ['pending','canceled'], __status($trnx->status, 'icon')) }}">
                                <span class="d-none">{{ ($trnx->status=='onhold') ? ucfirst('pending') : ucfirst($trnx->status) }}</span>
                            </div>
                            <div class="fake-class">
                                <span class="lead tnx-id">{{ $trnx->tnx_id }}</span>
                                <span class="sub sub-date">{{_date($trnx->tnx_time)}}</span>
                            </div>
                        </div>
                    </td>
                    <td class="data-col dt-token">
                        <span class="lead token-amount{{ $text_danger }}">{{ (starts_with($trnx->total_tokens, '-') ? '' : '+').$trnx->total_tokens }}</span>
                        <span class="sub sub-symbol">{{ token_symbol() }}</span>
                    </td>
                    <td class="data-col dt-amount{{ $text_danger }}">
                        @if ($trnx->tnx_type=='referral'||$trnx->tnx_type=='bonus') 
                            <span class="lead amount-pay">{{ '~' }}</span>
                        @else 
                        <span class="lead amount-pay{{ $text_danger }}">{{ to_num($trnx->amount, 'max') }}</span>
                        <span class="sub sub-symbol">{{ strtoupper($trnx->currency) }} <em class="fas fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="1 {{ token('symbol') }} = {{ to_num($trnx->currency_rate, 'max').' '.strtoupper($trnx->currency) }}"></em></span>
                        @endif
                    </td>
                    <td class="data-col dt-usd-amount">
                        @if ($trnx->tnx_type=='referral'||$trnx->tnx_type=='bonus') 
                            <span class="lead amount-pay">{{ '~' }}</span>
                        @else 
                        <span class="lead amount-pay{{ $text_danger }}">{{ to_num($trnx->base_amount, 'auto') }}</span>
                        <span class="sub sub-symbol">{{ base_currency(true) }} <em class="fas fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="1 {{ token('symbol') }} = {{ to_num($trnx->base_currency_rate, 'max').' '.base_currency(true) }}"></em></span>
                        @endif
                    </td>
                    <td class="data-col dt-point">
                        <span class="lead amount-pay{{ $text_danger }}">{{ to_num($trnx->point, 'auto') }}</span>
                        <span class="sub sub-symbol">POINT</span>
                       
                    </td>
                    
                    <td class="data-col dt-account">
                        @php 
                        $pay_to = ($trnx->payment_method=='system') ? '~' : ( ($trnx->payment_method=='bank') ? explode(',', $trnx->payment_to) : show_str($trnx->payment_to) );
                        $extra = ($trnx->tnx_type == 'refund') ? (is_json($trnx->extra, true) ?? $trnx->extra) : '';
                        @endphp
                        @if($trnx->tnx_type == 'refund')
                            <span class="sub sub-info">{{ $trnx->details }}</span>
                            @if($extra->trnx)
                            {{-- <span class="sub sub-view"><a href="javascript:void(0)" class="view-transaction" data-id="{{ $extra->trnx }}">View Transaction</a></span> --}}
                            @endif
                        @elseif($trnx->tnx_type == 'exchange')
                            <span class="lead user-info">{{ $trnx->name }}</span>
                            <span class="sub sub-date">{{ ($trnx->checked_time) ? _date($trnx->checked_time) : _date($trnx->created_at) }}</span>
                        @else
                            @if($trnx->refund != null)
                            <span class="sub sub-info text-danger">{{ __('Refunded #:orderid', ['orderid' => set_id($trnx->refund, 'refund')]) }}</span>
                            @else
                            <span class="lead user-info">{{ ($trnx->payment_method=='bank') ? $pay_to[0] : ( ($pay_to) ? $pay_to : '~' ) }}</span>
                            @endif
                            <span class="sub sub-date">{{ ($trnx->checked_time) ? _date($trnx->checked_time) : _date($trnx->created_at) }}</span>
                        @endif
                    </td>
                    <td class="data-col dt-type">
                        <span class="dt-type-md badge badge-outline badge-md badge-{{ __(__status($trnx->tnx_type,'status')) }}">{{ ucfirst($trnx->tnx_type) }}</span>
                        <span class="dt-type-sm badge badge-sq badge-outline badge-md badge-{{ __(__status($trnx->tnx_type, 'status')) }}">{{ ucfirst(substr($trnx->tnx_type, 0,1)) }}</span>
                    </td>
                    <td class="data-col text-right">
                        @if($trnx->status == 'pending' || $trnx->status == 'onhold')
                            @if($trnx->tnx_type != 'transfer')
                            <div class="relative d-inline-block d-md-none">
                                <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger"><em class="ti ti-more-alt"></em></a>
                                <div class="toggle-class dropdown-content dropdown-content-center-left pd-2x">
                                    <ul class="data-action-list">
                                        <li><a href="javascript:void(0)" class="btn btn-auto btn-primary btn-xs view-transaction" data-id="{{ $trnx->id }}"><span>{{__('Pay')}}</span><em class="ti ti-wallet"></em></a></li>
                                        @if($trnx->checked_time != NUll)
                                        <li><a href="{{ route('user.ajax.transactions.delete', $trnx->id) }}" class="btn btn-danger-alt btn-xs btn-icon user_tnx_trash" data-tnx_id="{{ $trnx->id }}"><em class="ti ti-trash"></em></a></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                               
                            <ul class="data-action-list d-none d-md-inline-flex">
                                @if($trnx->tnx_type != 'exchange')
                                <li><a href="javascript:void(0)" class="btn btn-auto btn-primary btn-xs view-transaction" data-id="{{ $trnx->id }}"><span>{{__('Pay')}}</span><em class="ti ti-wallet"></em></a></li>
                                @endif
                                @if($trnx->checked_time != NUll)
                                <li><a href="{{ route('user.ajax.transactions.delete', $trnx->id) }}" class="btn btn-danger-alt btn-xs btn-icon user_tnx_trash" data-tnx_id="{{ $trnx->id }}"><em class="ti ti-trash"></em></a></li>
                                @endif
                            </ul>
                            @else
                                <a href="javascript:void(0)" class="view-transaction btn btn-light-alt btn-xs btn-icon" data-id="{{ $trnx->id }}"><em class="ti ti-eye"></em></a>
                            @endif
                        @else
                        <a href="javascript:void(0)" class="view-transaction btn btn-light-alt btn-xs btn-icon" data-id="{{ $trnx->id }}"><em class="ti ti-eye"></em></a>
                            @if($trnx->checked_time == NUll && ($trnx->status == 'rejected' || $trnx->status == 'canceled'))
                            <a href="{{ route('user.ajax.transactions.delete', $trnx->id) }}" class="btn btn-danger-alt btn-xs btn-icon user_tnx_trash" data-tnx_id="{{ $trnx->id }}"><em class="ti ti-trash"></em></a>
                            @endif
                        @endif
                    </td>
                </tr>{{-- .data-item --}}
                @endforeach
            </tbody>
        </table>
    </div>{{-- .card-innr --}}
</div>{{-- .card --}}
@endsection

@section('modals')

<div class="modal fade" id="addTnx" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <h3 class="popup-title">Receiver's information</h3>
                <form action="{{ route('user.ajax.transactions.send') }}" method="POST" class="adduser-form validate-modern" id="addTranForm" autocomplete="false">
                    @csrf
                    <div class="row user">
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Email Address</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" required="required" name="email" type="email" placeholder="Email address" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Last 4 digits of the phone</label>
                                <div class="input-wrap">
                                    <input name="phone" class="input-bordered" minlength="4" maxlength="4" placeholder="Phone number" type="text" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 d-none">
                            <div class="alert alert-warning"></div>
                        </div>
                    </div>    
                    <hr />
                    <div class="row">
                        <div class="col-sm-6 token">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Number of Token</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" required="required" name="token" type="text" placeholder="0.1" autocomplete="off">
                                </div>
								<div class="note note-plane note-info pdb-1x">
									<em class="fas fa-info-circle"></em>
									<p>We will charge you a 0.4% transaction fee per transaction.</p>
								</div>
                            </div>
                            <div class="alert alert-warning d-none"></div>
                        </div>    
                        <div class="col-sm-6 d-none otp">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">OTP code</label>
                                <div class="input-wrap">
                                    <input name="otp" class="input-bordered" minlength="5" maxlength="5" placeholder="OTP Code" type="text" autocomplete="off">
                                </div>
                            </div>
                        </div>                    
                    </div>    
                    <div class="gaps-1x"></div>
                    <button class="btn btn-md btn-primary" type="submit">Send</button>                    
                </form>
            </div>
        </div>
        {{-- .modal-content --}}
    </div>
    {{-- .modal-dialog --}}
</div>
@endsection

@section('script')
<script>
    $('form#addTranForm').submit(function(e) {
        e.preventDefault();
        $('#addTranForm .user .alert').text('').parent().addClass('d-none');
        $('#addTranForm .token .alert').text('').addClass('d-none');
        $.post('{{ route('user.ajax.transactions.send') }}',  $(this).serialize()).done(i => {
            cl(i)
            if(i.msg === 'warning') {
                $('#addTranForm .user .alert').text(i.message).parent().removeClass('d-none');
            }
            if(i.msg === 'warning_token') {
                $('#addTranForm .token .alert').text(i.message).removeClass('d-none');
            }
            if(i.msg === 'info') {
                $('#addTranForm').find('input').attr('disabled', true);
                $('#addTranForm').find('input[name="otp"]').attr('disabled', false);
                $('#addTranForm').find('.otp').removeClass('d-none');
                $('#addTranForm').find('button[type="submit"]').text('Verify code').addClass('btn-warning').addClass('float-right');
             }

             if(i.msg === 'success') {
				$('#addTranForm').find('input').val('');					
                swal(i.message, "", "success").then( i => {
                    setTimeout(function() {
                        window.location.reload()
                    }, 150)
                });
             }
        })
    })
</script>
@endsection