@extends('layouts.user')
@section('title', __('Invests'))

@section('content')
    @include('layouts.messages')

    <div class="card content-area content-area-mh">
        <div class="card-innr">
            <div class="card-head">
                <h4 class="card-title">{{__('Invests')}}</h4>
                <div class="card-text">
                    <p>If you want to buy or sell your One Blue. Just do it here!</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs nav-tabs-line" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#buy-tab">Buy One Blue</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#sell-tab">My Cases</a>
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
                                <th class="data-col dt-seller-phone">{{ __('Seller last 4 digit phone') }}</th>
                                <th class="data-col dt-base-amount">{{ __('Amount') }}</th>
                                <th class="data-col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sellers as $seller)
                            <tr class="data-item tnx-item-{{ $seller->id }}">
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
                                    <span class="lead dt-email">{{$seller->email}}</span>
                                    <span class="sub sub-symbol"><i class="fa fa-envelope"></i></span>
                                </td>  
                                <td class="data-col dt-seller-mobile">
                                    <span class="lead dt-mobile">{{substr($seller->mobile, -4)}}</span>
                                    <span class="sub sub-symbol"><i class="fa fa-phone"></i></span>
                                </td>                         
                                <td class="data-col dt-seller-amount">
                                    <span class="lead dt-amount">{{$seller->amount}}</span>
                                    <span class="sub sub-symbol">ONE</span>
                                </td> 
                                <td class="data-col">
                                    <a href="javascript:;" class="btn btn-sm btn-info" style="min-width: 80px;" id="buyOne"><i class="fa fa-handshake"></i> Buy</a>
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
                                        <div class="data-state data-state-progress">
                                            <span class="d-none">{{ __($case->status) }}</span>
                                        </div>
                                        <div class="fake-class">
                                            <span class="lead tnx-id">{{ $case->id }}</span>
                                            <span class="sub sub-date">{{_date($case->created_at)}}</span>
                                        </div>
                                    </div>
                                </td>  
                                <td class="data-col dt-buyer">
                                <span class="lead dt-buyer-email">@if(empty($case->buyer_id)) None @else {{App\Http\Controllers\User\InvestController::buyer($case->buyer_id)}} @endif</span>
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
                                    <a href="javascript:;" class="btn btn-sm btn-info viewCase" style="min-width: 80px;" data-case-id="{{$case->id}}"><i class="fa fa-eye"></i> View</a>
                                <a href="javascript:;" class="btn btn-sm btn-danger cancelCase" style="min-width: 80px;" data-case-id="{{$case->id}}"><i class="fa fa-trash"></i> Cancel</a>
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

            $("#buyOne").on('click', function(){
                email = $('.dt-email').text();
                mobile = $('.dt-mobile').text();
                amount = parseFloat($('.dt-amount').text());
                fee = (amount * 25) / 100;

                $('#seller_email').val(email);
                $('#seller_mobile').val(mobile);
                $('#seller_amount').val(amount);

                $('#selectMethodBuy').modal('show');
            });

            $("#safetyMethod").on('click', function(){
                var admin_fee = (amount * 5) / 100;

                $('#total_receive').val((amount + fee) - admin_fee);

                $('#selectMethodBuy').modal('hide');
                $('#confirmBuyModal').modal('show');
            });

            $("#directMethod").on('click', function(){
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

                $.post("{{route('user.ajax.invest.view')}}", {case_id: id, _token: '{{csrf_token()}}' },
                function(data,status){
                    $('#viewCaseId').html(id);
                    $('#viewCaseContent').html(data);
                    $('#viewCaseModal').modal('show');
                });                
            });

            $('.cancelCase').on('click', function(){
                var id = $(this).data('case-id');
                $('#cancelCaseIdText').text(id);
                $('#cancelCaseId').val(id);

                $('#confirmCancelModal').modal('show');
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
                        <img src="https://s28.postimg.cc/ju5bnc3x9/plane.png" alt="" class="pricing-img">
                        <h2 class="pricing-header">Safety Method</h2>
                        <ul class="pricing-features">
                            <li class="pricing-features-item">Admin Guaranteed</li>
                            <li class="pricing-features-item text-success">With 5% fee per time, you don't need to worry about scam.</li>
                        </ul>
                        <a href="javascript:;" id="safetyMethod" class="pricing-button is-featured">SELECT</a>
                    </div>
                    <div class="pricing-plan">
                        <img src="https://s21.postimg.cc/tpm0cge4n/space-ship.png" alt="" class="pricing-img">
                        <h2 class="pricing-header">Direct Method</h2>
                        <ul class="pricing-features">
                            <li class="pricing-features-item">Only seller & buyer</li>
                            <li class="pricing-features-item text-danger">WARNING* : We will not be responsible if there is have any problem.</li>
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
                    <h3 class="popup-title">Seller information:</h3>
                    <form action="{{ route('user.ajax.invest.send') }}" method="POST" class="adduser-form validate-modern" id="addTranForm" autocomplete="false">
                        @csrf
                        <div class="row user">
                            <div class="col-sm-6">
                                <div class="input-item input-with-label">
                                    <label class="input-item-label">Email Address</label>
                                    <div class="input-wrap">
                                        <input class="input-bordered" required="required" name="email" type="email" id="seller_email" placeholder="Email address" readonly>
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
                    <form action="{{ route('user.ajax.invest.sell') }}" method="POST" class="adduser-form validate-modern" id="addTranForm" autocomplete="false">
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
                    <h3 class="popup-title">Confirm cancel case #<span id="cancelCaseIdText">0</span>:</h3>
                    <p>Are you sure you want to cancel this case ?</p>
                    <form action="{{ route('user.ajax.invest.cancel') }}" method="POST" class="adduser-form validate-modern" id="addTranForm" autocomplete="false">
                        @csrf
                        <input type="hidden" name="cancelCaseId" id="cancelCaseId" value="">
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

