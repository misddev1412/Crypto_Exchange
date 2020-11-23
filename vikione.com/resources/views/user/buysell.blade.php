@extends('layouts.user')
@section('title', __('Buy / Sell'))

@section('customStyle')
<style>
.inbox_people {
  background: #f8f8f8 none repeat scroll 0 0;
  float: left;
  overflow: hidden;
  width: 40%; border-right:1px solid #c4c4c4;
}
.inbox_msg {
  clear: both;
  overflow: hidden;
}
.top_spac{ margin: 20px 0 0;}


.recent_heading {float: left; width:40%;}
.srch_bar {
  display: inline-block;
  text-align: right;
  width: 60%; padding:
}
.headind_srch{ padding:10px 29px 10px 20px; overflow:hidden; border-bottom:1px solid #c4c4c4;}

.recent_heading h4 {
  color: #05728f;
  font-size: 21px;
  margin: auto;
}
.srch_bar input{ border:1px solid #cdcdcd; border-width:0 0 1px 0; width:80%; padding:2px 0 4px 6px; background:none;}
.srch_bar .input-group-addon button {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  padding: 0;
  color: #707070;
  font-size: 18px;
}
.srch_bar .input-group-addon { margin: 0 0 0 -27px;}

.chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
.chat_ib h5 span{ font-size:13px; float:right;}
.chat_ib p{ font-size:14px; color:#989898; margin:auto}
.chat_img {
  float: left;
  width: 11%;
}
.chat_ib {
  float: left;
  padding: 0 0 0 15px;
  width: 88%;
}

.chat_people{ overflow:hidden; clear:both;}
.chat_list {
  border-bottom: 1px solid #c4c4c4;
  margin: 0;
  padding: 18px 16px 10px;
}
.inbox_chat { height: 550px; overflow-y: scroll;}

.active_chat{ background:#ebebeb;}

.incoming_msg_img {
  display: inline-block;
  width: 6%;
}
.received_msg {
  display: inline-block;
  padding: 0 0 0 10px;
  vertical-align: top;
  width: 92%;
 }
 .received_withd_msg p {
  background: #ebebeb none repeat scroll 0 0;
  border-radius: 3px;
  color: #646464;
  font-size: 14px;
  margin: 0;
  padding: 5px 10px 5px 12px;
  width: 100%;
}
.time_date {
  color: #747474;
  display: block;
  font-size: 12px;
  margin: 8px 0 0;
}
.received_withd_msg { width: 57%;}
.mesgs {
  float: left;
  padding: 30px 15px 0 25px;
  width: 100%;
}

 .sent_msg p {
  background:#d8990e none repeat scroll 0 0;
  border-radius: 3px;
  font-size: 14px;
  margin: 0; color:#fff;
  padding: 5px 10px 5px 12px;
  width:100%;
}
.outgoing_msg{ overflow:hidden; margin:26px 0 26px;}
.sent_msg {
  float: right;
  width: 46%;
}
.input_msg_write input {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  color: #4c4c4c;
  font-size: 15px;
  min-height: 48px;
  width: 100%;
}

.type_msg {border-top: 1px solid #c4c4c4;position: relative;}
.msg_send_btn {
  background: #05728f none repeat scroll 0 0;
  border: medium none;
  border-radius: 50%;
  color: #fff;
  cursor: pointer;
  font-size: 17px;
  height: 33px;
  position: absolute;
  right: 0;
  top: 11px;
  width: 33px;
}
.messaging { padding: 0 0 50px 0;}
.msg_history {
  height: 516px;
  overflow-y: auto;
}
</style>
@endsection
@section('content')
    @include('layouts.messages')

    <div class="card content-area content-area-mh">
        <div class="card-innr">
            <div class="card-head">
                <h4 class="card-title">{{__('Buy / Sell')}}</h4>
                <div class="card-text">
                    <p>If you want to buy or sell your One Blue. Just do it here!</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs nav-tabs-line" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#buy-tab">ONE MARKET</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#sell-tab">SELL YOUR ONE BLUE</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#history-tab">HISTORY</a>
                        </li>                                                
                    </ul>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="buy-tab">      
                    <table class="data-table dt-filter-init user-tnx">
                        <thead>
                            <tr class="data-item data-head">
                                <th class="data-col tnx-status dt-no">{{ __('No.') }}</th>
                                <th class="data-col dt-seller-email">{{ __('Seller email') }}</th>
                                <th class="data-col dt-seller-mobile">{{ __('Seller last 4 digit phone') }}</th>
                                <th class="data-col dt-seller-amount">{{ __('Amount') }}</th>
                                <th class="data-col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sellers as $seller)
                            <tr class="data-item data-item-{{ $seller->id }}">
                                <td class="data-col dt-no">
                                    <div class="d-flex align-items-center">
                                        <div class="data-state data-state-progress">
                                            <span class="d-none">{{ __($seller->status) }}</span>
                                        </div>
                                        <div class="fake-class">
                                            <span class="lead tnx-id">{{ $seller->id }}</span>
                                            <span class="sub sub-date">{{_date($seller->created_at)}}</span>
                                        </div>
                                    </div>
                                </td>  
                                <td class="data-col dt-seller-email">
                                    <span class="lead dt-seller-email">{{$seller->email}}</span>
                                    <span class="sub sub-symbol"><i class="fa fa-envelope"></i></span>
                                </td>  
                                <td class="data-col dt-seller-mobile">
                                    <span class="lead dt-seller-mobile">{{substr($seller->mobile, -4)}}</span>
                                    <span class="sub sub-symbol"><i class="fa fa-phone"></i></span>
                                </td>                         
                                <td class="data-col dt-seller-amount">
                                    <span class="lead dt-seller-amount">{{$seller->amount}}</span>
                                    <span class="sub sub-symbol">ONE</span>
                                </td> 
                                <td class="data-col">
                                <a href="javascript:;" class="btn btn-sm btn-info buyOne" style="min-width: 80px;" data-id="{{$seller->id}}"><i class="fa fa-handshake"></i> Buy</a>
                                </td>                                                                                          
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="sell-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#sellOne"><i class="fa fa-plus-circle"></i> Sell your One Blue</button>
                        </div>
                    </div>
                    <table class="data-table dt-filter-init user-tnx">
                        <thead>
                            <tr class="data-item data-head">
                                <th class="data-col dt-status dt-no">{{ __('No.') }}</th>
                                <th class="data-col dt-buyer-email">{{ __('Buyer email') }}</th>
                                <th class="data-col dt-base-amount">{{ __('Amount') }}</th>
                                <th class="data-col dt-status">{{ __('Status') }}</th>                                
                                <th class="data-col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($my_cases as $case)
                            <tr class="data-item tnx-item-{{ $case->id }}">
                                <td class="data-col dt-no">
                                    <div class="d-flex align-items-center">
                                        <div class="data-state data-state-{{ __($case->status) }}">
                                            <span class="d-none">{{ __($case->status) }}</span>
                                        </div>
                                        <div class="fake-class">
                                            <span class="lead tnx-id">{{ $case->id }}</span>
                                            <span class="sub sub-date">{{_date($case->created_at)}}</span>
                                        </div>
                                    </div>
                                </td>  
                                <td class="data-col dt-buyer">
                                <span class="lead dt-buyer-email">@if(empty($case->buyer_id)) None @else {{App\Http\Controllers\User\BuySellController::buyer($case->buyer_id)}} @endif</span>
                                    <span class="sub sub-symbol"><i class="fa fa-user"></i></span>
                                </td>                         
                                <td class="data-col dt-seller-amount">
                                    <span class="lead dt-amount">{{$case->amount}}</span>
                                    <span class="sub sub-symbol">ONE</span>
                                </td> 
                                <td class="data-col dt-seller-status text-center">
                                    <span class="lead dt-status">
                                        <span class="badge badge-@if($case->status == 'pending')info @elseif($case->status == 'progress')warning @elseif($case->status == 'canceled')danger @else{{'success'}} @endif">
                                            {{strtoupper($case->status)}}
                                        </span>
                                    </span>
                                </td>                                 
                                <td class="data-col text-center">
                                    <a href="javascript:;" class="btn btn-sm btn-info viewCase" style="min-width: 80px;" data-case-id="{{$case->id}}" data-case-type="seller"><i class="fa fa-eye"></i> View</a>
                                <a href="javascript:;" class="btn btn-sm btn-danger cancelCase" style="min-width: 80px;" data-case-id="{{$case->id}}" data-case-type="seller"><i class="fa fa-trash"></i> Cancel</a>
                                </td>                                                                                          
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="history-tab">
                    <table class="data-table dt-filter-init user-tnx">
                        <thead>
                            <tr class="data-item data-head">
                                <th class="data-col dt-history-status dt-no">{{ __('No.') }}</th>
                                <th class="data-col dt-history-email">{{ __('Seller Email') }}</th>
                                <th class="data-col dt-history-amount">{{ __('Amount') }}</th>
                                <th class="data-col dt-history-status">{{ __('Status') }}</th>                                
                                <th class="data-col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($histories as $item)
                            <tr class="data-item tnx-item-{{ $item->id }}">
                                <td class="data-col dt-no">
                                    <div class="d-flex align-items-center">
                                        <div class="data-state data-state-{{ __($item->status) }}">
                                            <span class="d-none">{{ __($item->status) }}</span>
                                        </div>
                                        <div class="fake-class">
                                            <span class="lead tnx-id">{{ $item->id }}</span>
                                            <span class="sub sub-date">{{_date($item->created_at)}}</span>
                                        </div>
                                    </div>
                                </td>  
                                <td class="data-col dt-history">
                                <span class="lead dt-history-email">{{App\Http\Controllers\User\BuySellController::seller($item->seller_id)}}</span>
                                    <span class="sub sub-symbol"><i class="fa fa-user"></i></span>
                                </td>                         
                                <td class="data-col dt-history-amount">
                                    <span class="lead dt-history-amount">{{$item->amount}}</span>
                                    <span class="sub sub-symbol">ONE</span>
                                </td> 
                                <td class="data-col dt-history-status text-center">
                                    <span class="lead dt-history-status">
                                        <span class="badge badge-@if($item->status == 'pending')info @elseif($item->status == 'progress')warning @elseif($item->status == 'canceled')danger @else{{'success'}} @endif">
                                            {{strtoupper($item->status)}}
                                        </span>
                                    </span>
                                </td>                                 
                                <td class="data-col text-center">
                                    <a href="javascript:;" class="btn btn-sm btn-info viewCase" style="min-width: 80px;" data-case-id="{{$item->id}}" data-case-type="buyer"><i class="fa fa-eye"></i> View</a>
                                <a href="javascript:;" class="btn btn-sm btn-danger cancelCase" style="min-width: 80px;" data-case-id="{{$item->id}}" data-case-type="buyer"><i class="fa fa-trash"></i> Cancel</a>
                                </td>                                                                                          
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                    
                </div>
            </div>                     
        </div>
    </div>

    @section('script')
        <script>
            var email;
            var mobile;
            var amount;
            var fee;

            $('form').on('submit', function(){
                var btn = $(this).find('button[type=submit]');
                btn.addClass('disabled');
                btn.css('pointer-events', 'none');
                btn.html('<i class="fa fa-spinner fa-spin"></i>');
            })

            $(".buyOne").on('click', function(){
                var id = $(this).data('id');
                var elem = $('.data-item-'+id);

                email = elem.find('.dt-seller-email').text();
                mobile = elem.find('.dt-seller-mobile').text();
                amount = parseFloat(elem.find('.dt-seller-amount').text());

                fee = (amount * 25) / 100;

                $('#seller_email').val(email);
                $('#seller_mobile').val(mobile.trim());
                $('#seller_amount').val(amount);
                $('#seller_hidden_id').val(id);
                $('#seller_case_id').text(id);

                $('#selectMethodBuy').modal('show');
            });

            $("#safetyMethod").on('click', function(){
                var admin_fee = (amount * 5) / 100;
                $('#method').val('safety');
                $('#total_receive').val((amount + fee) - admin_fee);

                $('#selectMethodBuy').modal('hide');
                $('#confirmBuyModal').modal('show');
            });

            $("#directMethod").on('click', function(){
                $('#method').val('direct');
                $('#total_receive').val(amount + fee);
                
                $('#selectMethodBuy').modal('hide');
                $('#confirmBuyModal').modal('show');
            });
            
            $('#sell_amount').on('input', function(){
                var input = parseFloat($(this).val());
                var total = input + ((input * 25) / 100);

                $('#total_due').val(total);
            })

            $('.viewCase').on('click', function(){
                var id = $(this).data('case-id');
                var type = $(this).data('case-type');

                $.post("{{route('user.ajax.buysell.view')}}", {case_id: id, case_type: type, _token: '{{csrf_token()}}' },
                function(data,status){
                    $('#viewCaseId').html(id);
                    $('#viewCaseContent').html(data);
                    $('#viewCaseModal').modal('show');
                });                
            });          

            $('.cancelCase').on('click', function(){
                var id = $(this).data('case-id');
                var type = $(this).data('case-type');

                $('#cancelIdText').text(id);
                $('#cancel_id').val(id);
                $('#cancel_type').val(type);

                $('#confirmCancelModal').modal('show');
            });
            
            $(document).on('submit','#sendMsgForm', function(e){
                e.preventDefault();
                var id = $(this).data('case-id');
                var msg = $(this).find('.write_msg').val();

                $.post("{{route('user.ajax.buysell.message')}}", {case_id: id, message: msg, _token: '{{csrf_token()}}' },
                function(data,status){
                    $('#viewCaseContent').html(data);
                });  
            });
        </script>
    @endsection 

    @section('modals')
    
    <div class="modal fade" id="selectMethodBuy" tabindex="-1">
        <div class="modal-dialog modal-dialog-lg modal-dialog-centered">
            <div class="modal-content">
                <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
                <div class="panel pricing-table">
                    <div class="pricing-plan">
                        <img src="{{asset('images/icons/shield.png')}}" alt="" class="pricing-img">
                        <h2 class="pricing-header">Safety Method</h2>
                        <ul class="pricing-features">
                            <li class="pricing-features-item">Admin Guaranteed</li>
                            <li class="pricing-features-item">5% Fee</li>
                            <li class="pricing-features-item text-success">You don't need to worry about scam.</li>
                        </ul>
                        <a href="javascript:;" id="safetyMethod" class="pricing-button is-featured">SELECT</a>
                    </div>
                    <div class="pricing-plan">
                        <img src="{{asset('images/icons/risk.png')}}" alt="" class="pricing-img">
                        <h2 class="pricing-header">Direct Method</h2>
                        <ul class="pricing-features">
                            <li class="pricing-features-item">Only seller & buyer</li>
                            <li class="pricing-features-item">FREE</li>
                            <li class="pricing-features-item text-danger">We will not be responsible if there is have any problem.</li>
                        </ul>
                        <a href="javascript:;" id="directMethod" class="pricing-button">SELECT</a>
                    </div>
                </div>
            </div>
            {{-- .modal-content --}}
        </div>
        {{-- .modal-dialog --}}
    </div>

    <div class="modal fade" id="confirmBuyModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-md modal-dialog-centered">
            <div class="modal-content">
                <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
                <div class="popup-body popup-body-md">
                    <h3 class="popup-title">Confirm buy One Blue #<span id="seller_case_id">naN</span></h3>
                    <form action="{{ route('user.ajax.buysell.send') }}" method="POST" class="adduser-form validate-modern" id="addTranForm" autocomplete="false">
                        @csrf
                        <input type="hidden" id="method" name="method">
                        <input type="hidden" id="seller_hidden_id" name="seller_hidden_id">
                        <div class="row user">
                            <div class="col-sm-6">
                                <div class="input-item input-with-label">
                                    <label class="input-item-label">Email Address</label>
                                    <div class="input-wrap">
                                        <input class="input-bordered" required="required" type="email" id="seller_email" placeholder="Email address" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-item input-with-label">
                                    <label class="input-item-label">Last 4 digits of the phone</label>
                                    <div class="input-wrap">
                                        <input name="phone" class="input-bordered" minlength="4" maxlength="4" placeholder="Phone number" id="seller_mobile" type="text" readonly>
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
                                    <label class="input-item-label">Total you'll pay (USD):</label>
                                    <div class="input-wrap">
                                        <input class="input-bordered" type="number" min="0" id="seller_amount" readonly>
                                    </div>
                                    <div class="note note-plane note-info pdb-1x">
                                        <em class="fas fa-info-circle"></em>
                                        <p>You will receive Seller amount + 25%.</p>
                                    </div>
                                </div>
                                <div class="alert alert-warning d-none"></div>
                            </div>    
                            <div class="col-sm-6 actually-token">
                                <div class="input-item input-with-label">
                                    <label class="input-item-label">Actually you will receive (One Blue).</label>
                                    <div class="input-wrap">
                                        <input class="input-bordered" name="token" readonly type="text" id="total_receive">
                                    </div>
                                    
                                </div>
                                <div class="alert alert-warning d-none"></div>
                            </div>    
                            <div class="col-sm-12 d-none otp">
                                <div class="input-item input-with-label">
                                    <label class="input-item-label">OTP code</label>
                                    <div class="input-wrap">
                                        <input name="otp" class="input-bordered" minlength="5" maxlength="5" placeholder="OTP Code" type="text">
                                    </div>
                                </div>
                            </div>                    
                        </div>    
                        <div class="gaps-1x"></div>
                        <button class="btn btn-md btn-primary" type="submit">Submit</button>                    
                    </form>
                </div>
            </div>
            {{-- .modal-content --}}
        </div>
        {{-- .modal-dialog --}}
    </div>

    <div class="modal fade" id="sellOne" tabindex="-1">
        <div class="modal-dialog modal-dialog-md modal-dialog-centered">
            <div class="modal-content">
                <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
                <div class="popup-body popup-body-md">
                <h3 class="popup-title">Sell your One Blue (Your Balance: {{$user->tokenBalance2}} ONE)</h3>
                    <form action="{{ route('user.ajax.buysell.sell') }}" method="POST" class="adduser-form validate-modern" id="addTranForm" autocomplete="false">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6 token">
                                <div class="input-item input-with-label">
                                    <label class="input-item-label">Amount you want to sell:</label>
                                    <div class="input-wrap">
                                        <input class="input-bordered" type="number" min="50" max="100000" name="amount" id="sell_amount" value="0" required>
                                    </div>
                                    <div class="note note-plane note-info pdb-1x">
                                        <em class="fas fa-info-circle"></em>
                                        <p>Minimum amount: 50 One Blue.</p>
                                    </div>
                                </div>
                                <div class="alert alert-warning d-none"></div>
                            </div>    
                            <div class="col-sm-6 actually-token">
                                <div class="input-item input-with-label">
                                    <label class="input-item-label">Actually amount you will send:</label>
                                    <div class="input-wrap">
                                        <input class="input-bordered disabled" name="total_due"  type="text" id="total_due" value="0" readonly>
                                    </div>
                                    <div class="note note-plane note-info pdb-1x">
                                        <em class="fas fa-info-circle"></em>
                                        <p>Fee = Amount + 25%.</p>
                                    </div>                                    
                                </div>
                                <div class="alert alert-warning d-none"></div>
                            </div>    
                            <div class="col-sm-12 d-none otp">
                                <div class="input-item input-with-label">
                                    <label class="input-item-label">OTP code</label>
                                    <div class="input-wrap">
                                        <input name="otp" class="input-bordered" minlength="5" maxlength="5" placeholder="OTP Code" type="text">
                                    </div>
                                </div>
                            </div>                    
                        </div>    
                        <div class="gaps-1x"></div>
                        <button class="btn btn-md btn-primary" type="submit">Submit</button>                    
                    </form>
                </div>
            </div>
            {{-- .modal-content --}}
        </div>
        {{-- .modal-dialog --}}
    </div> 
    
    
    <div class="modal fade" id="viewCaseModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-sm modal-dialog-centered">
            <div class="modal-content">
                <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
                <div class="popup-body popup-body-sm">
                    <h3 class="popup-title">Case #<span id="viewCaseId">Nan</span>:</h3>
                    <div id="viewCaseContent"></div>
                </div>
            </div>
            {{-- .modal-content --}}
        </div>
        {{-- .modal-dialog --}}
    </div>    

    <div class="modal fade" id="confirmCancelModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-sm modal-dialog-centered">
            <div class="modal-content">
                <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
                <div class="popup-body popup-body-sm">
                    <h3 class="popup-title">Confirm cancel case #<span id="cancelIdText">0</span>:</h3>
                    <p>Are you sure you want to cancel this case ?</p>
                    <form action="{{ route('user.ajax.buysell.cancel') }}" method="POST" class="adduser-form validate-modern" id="addTranForm" autocomplete="false">
                        @csrf
                        <input type="hidden" name="cancel_type" id="cancel_type" value="">
                        <input type="hidden" name="cancel_id" id="cancel_id" value="">
                        <button class="btn btn-md btn-primary" type="submit">YES</button>
                    </form>
                </div>
            </div>
            {{-- .modal-content --}}
        </div>
        {{-- .modal-dialog --}}
    </div>    
    @endsection
@endsection

