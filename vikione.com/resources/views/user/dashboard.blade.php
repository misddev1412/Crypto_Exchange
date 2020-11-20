@extends('layouts.user')
@section('title', __('User Dashboard'))
@php
$has_sidebar = false;
$base_currency = base_currency();
@endphp

@section('content')
<div class="content-area user-account-dashboard">
    @include('layouts.messages')
    <div class="row">
        <div class="col-lg-4">
            {!! UserPanel::user_balance_card($contribution, ['vers' => 'side', 'class'=> 'card-full-height']) !!}
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="account-info card">
                <div class="card-innr">
          {!! UserPanel::user_account_point_status() !!}        
                </div>
                </div>       
		</div>
        <div class="col-lg-4">
            {!! UserPanel::user_balance2_card($contribution, ['vers' => 'side', 'class'=> 'card-full-height']) !!}
        </div>
       </div>
    </div>
    <div class="row">
    <div class="col-lg-4 col-md-6">
          {!! UserPanel::user_token_block('', ['vers' => 'buy']) !!}           
      </div>
        <div class="col-lg-4 col-md-6">
            <div class="account-info card card-full-height">
                <div class="card-innr">
                    {!! UserPanel::user_account_status() !!}
                    <div class="gaps-2x"></div>
                    {!! UserPanel::user_account_wallet() !!}
                </div>
            </div>
        </div>
        @if(get_page('home_top', 'status') == 'active')
          <div class="col-12 col-lg-4">
            {!! UserPanel::token_sales_progress('',  ['class' => 'card-full-height']) !!}
        </div>
        @endif
    </div>
    <div class="row">
        @if(get_page('home_top', 'status') == 'active')
        <div class="col-12 col-lg-12">
            {!! UserPanel::content_block('welcome', ['image' => 'welcome.png', 'class' => 'card-full-height']) !!}
        </div>
       
        @endif

    </div>
</div>

{{-- Modal transaction pending --}}
<div class="modal fade" id="token-pending" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">PAYMENT SELECTION</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="method-payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var formPaymentPending = (tnxId) => {
        var data    = {tnx_id: tnxId}
        $.ajax({
            url: "{{route('ajax.transaction.payment.show')}}",
            type: 'GET',
            data: data,
            cache: false,
            dataType: 'json',

            success: function (data) {
                if (data.status == 1) {
                    $('#token-pending').modal('show')
                    $('#token-pending .modal-body').html(data.view)
                }
            },
            error: function () {
                // trumbowyg.addErrorOnModalField(
                //     $('input[type=text]', $modal),
                //     trumbowyg.lang.noembedError
                // );
            }
        });
    }

    var formPaymentMethod = (method, tnxID) => {
        var data    = {method: method, tnx_id: tnxID}
        $.ajax({
            url: "{{route('ajax.transaction.payment.method')}}",
            type: 'GET',
            data: data,
            cache: false,
            dataType: 'json',

            success: function (data) {
                if (data.status == 1) {
                    $('#method-payment').modal('show')
                    $('#method-payment .modal-content').html(data.view)
                }
            },
            error: function () {
                // trumbowyg.addErrorOnModalField(
                //     $('input[type=text]', $modal),
                //     trumbowyg.lang.noembedError
                // );
            }
        });
    }
</script>
<script>
    $('button#bridge').click(function(e) {
        e.preventDefault();
        $(this).attr('disabled', true).text(($(this).data('loading'))).parent().find('.spinner-grow').removeClass('d-none').addClass('d-block');
        var that = this;
        swal({
            title: "Are you sure?",
            text: "If you rotate the bridge, one's token will be converted to points based on any coefficient, the Points will be converted back into tokens for trading between users.",
            icon: 'warning',
            buttons: {
                cancel: {
                    text: "Cancel",
                    visible: !0
                },
                confirm: {
                    text: 'Yes',
                    className: ""
                }
            }
        })
        .then(i=>{
            if(i) {
                $.post('{{ route('user.ajax.account.point.multiply')}}', {_token: csrf_token})
                .done(i=> {
                    cl(i),
                    show_toast(i.msg, i.message),
                    void 0 !== i.reload && i.reload && setTimeout(function() {
                        window.location.reload()
                    }, 150)
                    if(i.msg === 'warning') {
                      $(that).attr('disabled', false).text(($(this).data('text'))).parent().find('.spinner-grow').removeClass('d-block').addClass('d-none')
                    }
                
            }).fail(function(t, e, a) {
                _log(t, e, a),
                show_toast("error", "Something is wrong!\n" + a)
                $(that).attr('disabled', false).text(($(this).data('text'))).parent().find('.spinner-grow').removeClass('d-block').addClass('d-none');
            })
            } else {
                $(that).attr('disabled', false).text(($(this).data('text'))).parent().find('.spinner-grow').removeClass('d-block').addClass('d-none');
            }
        })
    })
</script>

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
                swal(i.message, "", "success").then( i => {
					 $('#addTranForm').find('input').val('');	
                    setTimeout(function() {
                        window.location.reload()
                    }, 150)
                });
             }
        })
    })


    //Sell Good 


    $(".allownumericwithdecimal").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

    $("form#addSellGoodForm").submit(function(e){
        e.preventDefault();
        $.post('{{ route('user.ajax.sell_goods.send') }}',  $(this).serialize()).done(i => {
            cl(i)
            if(i.msg === 'error') {
                $('#addSellGoodForm .user .alert').text(i.message).parent().removeClass('d-none');
            }
             if(i.msg === 'success') {
                swal(i.message, "", "success").then( i => {
					 $('#addSellGoodForm').find('input').val('');	
                    setTimeout(function() {
                        window.location.reload()
                    }, 150)
                });
             }
        })
    })
</script>
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
                                    <input class="input-bordered" required="required" name="email" type="email" placeholder="Email address">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Last 4 digits of the phone</label>
                                <div class="input-wrap">
                                    <input name="phone" class="input-bordered" minlength="4" maxlength="4" placeholder="Phone number" type="text">
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
                                    <input class="input-bordered" required="required" name="token" type="number" min="0" placeholder="0.1">
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
                                    <input name="otp" class="input-bordered" minlength="5" maxlength="5" placeholder="OTP Code" type="text">
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

<div class="modal fade" id="selectMethodSell" tabindex="-1">
    <div class="modal-dialog modal-dialog-lg modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="panel pricing-table">
                <div class="pricing-plan">
                    <img src="https://s22.postimg.cc/8mv5gn7w1/paper-plane.png" alt="" class="pricing-img">
                    <h2 class="pricing-header">Invest</h2>
                    <ul class="pricing-features">
                        <li class="pricing-features-item">Custom domains</li>
                        <li class="pricing-features-item">Sleeps after 30 mins of inactivity</li>
                    </ul>
                    <span class="pricing-price">Free</span>
                    <a data-toggle="modal" data-target="#addTnx" class="pricing-button">SELECT</a>
                </div>
                <div class="pricing-plan">
                    <img src="https://s28.postimg.cc/ju5bnc3x9/plane.png" alt="" class="pricing-img">
                    <h2 class="pricing-header">Sell One</h2>
                    <ul class="pricing-features">
                        <li class="pricing-features-item">Never sleeps</li>
                        <li class="pricing-features-item">Multiple workers for more powerful apps</li>
                    </ul>
                    <span class="pricing-price">$150</span>
                    <a data-toggle="modal" data-target="#sellOne" class="pricing-button is-featured">SELECT</a>
                </div>
                <div class="pricing-plan">
                    <img src="https://s21.postimg.cc/tpm0cge4n/space-ship.png" alt="" class="pricing-img">
                    <h2 class="pricing-header">Enterprise</h2>
                    <ul class="pricing-features">
                        <li class="pricing-features-item">Dedicated</li>
                        <li class="pricing-features-item">Simple horizontal scalability</li>
                    </ul>
                    <span class="pricing-price">$400</span>
                    <a href="#/" class="pricing-button">Free trial</a>
                </div>
            </div>
        </div>
        {{-- .modal-content --}}
    </div>
    {{-- .modal-dialog --}}
</div>


{{-- <div class="modal fade" id="sellOne" tabindex="-1"> --}}


<div class="modal fade" id="addSellGoods" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <h3 class="popup-title">Receiver's information</h3>
                {{-- <form action="{{ route('user.ajax.transactions.send') }}" method="POST" class="adduser-form validate-modern" id="addTranForm" autocomplete="false"> --}}
                <form action="{{ route('user.ajax.sell_goods.send') }}" method="POST" class="adduser-form validate-modern" id="addSellGoodForm" autocomplete="false">
                    @csrf
                    <div class="row user">
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Email Address</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" required="required" name="email" type="email" placeholder="Email address">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Last 4 digits of the phone</label>
                                <div class="input-wrap">
                                    <input name="phone" class="input-bordered allownumericwithoutdecimal" minlength="4" maxlength="4" placeholder="Phone number" type="text">
                                </div>
                               
                            </div>
                        </div>
                        <div class="col-sm-12 d-none">
                            <div class="alert alert-danger"></div>
                        </div>
                    </div>    
                    <hr />
                    <div class="row">
                        <div class="col-sm-6 token">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Number of One Blue</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" required="required" oninput="incrementValue()" type="number" min="0" placeholder="0.1">
                                </div>
                                <div class="note note-plane note-info pdb-1x">
									<em class="fas fa-info-circle"></em>
									<p>You will have to transfer with a value of 125%.</p>
								</div>
                            </div>
                            <div class="alert alert-warning d-none"></div>
                        </div>    
                        <div class="col-sm-6 actually-token">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Actually you will send.</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" required="required" name="token" readonly type="number" min="0" placeholder="0.1">
                                </div>
                                
                            </div>
                            <div class="alert alert-warning d-none"></div>
                        </div>    
                        <div class="col-sm-12 d-none otp">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">OTP code</label>
                                <div class="input-wrap">
                                    <input name="otp" class="input-bordered" minlength="5" maxlength="5" placeholder="OTP Code" type="text">
                                <label class="input-item-label">Amount</label>
                                <div class="input-wrap">
                                    <input class="input-bordered allownumericwithdecimal" required="required" name="amount" type="text" placeholder="Amout">
                                </div>
                                <!-- <div class="note note-plane note-info pdb-1x">
									<em class="fas fa-info-circle"></em>
									<p>We will charge you a 0.4% transaction fee per transaction.</p>
								</div> -->
                            </div>
                            <div class="alert alert-success d-none"></div>
                        </div>    
                        <div class="col-sm-6 ">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Detail</label>
                                <div class="input-wrap">
                                    <input name="detail" class="input-bordered"  placeholder="Detail" type="text">
                                </div>
                            </div>
                        </div>                    
                    </div>    
                    <div class="gaps-1x"></div>
                    <button class="btn btn-md btn-primary" type="submit">Send request</button>                    
                </form>
            </div>
        </div>
        {{-- .modal-content --}}
    </div>
    {{-- .modal-dialog --}}
</div>
@endsection
