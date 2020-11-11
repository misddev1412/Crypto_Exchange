<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">PAYMENT INFORMATION</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="container banking-information-container">
        <div class="row">
            <div class="col-lg-12 mb-2">
                <p>Please pay us with the information below and select one bank to complete.</p>
            </div>
           
            <div class="col-lg-6 ">
                <label>
                    {{-- <input type="radio" name="bank" class="card-input-element d-none" value="acb" id="demo1"> --}}
                    <div class="card ">
                        <div class="card-body banking-information-card">
                            <div class="row ">
                                <div class="col-4">
                                    <img src="{{asset('assets/images/banking/acb.png')}}" alt="" width="200">
                                </div>
                                <div class="col-8 payment-information-right">
                                    {{-- <div class="banking-group">
                                        <label for="" class="font-weight-bold">Name: </label><span class="ml-1">Truong Minh Cang</span>
                                    </div> --}}
                                    <div class="banking-group">
                                        <label for="" class="font-weight-bold">Account no: </label><span class="ml-1">73 61 69</span>
                                    </div>
                                    <div class="banking-group">
                                        <label for="" class="font-weight-bold">Branch: </label><span class="ml-1">ACB Ho Chi Minh City</span>
                                    </div>
                                    <div class="banking-group">
                                        <label for="" class="font-weight-bold">Content: </label><span class="ml-1">Payment for #{{$tnxId}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>
                  
            </div>
            <div class="col-lg-6 ">
                <label>
                    {{-- <input type="radio" name="bank" class="card-input-element d-none" value="vcb" id="demo1"> --}}
                    <div class="card ">
                        <div class="card-body banking-information-card">
                            <div class="row ">
                                <div class="col-4">
                                    <img src="{{asset('assets/images/banking/vcb.png')}}" alt="" width="200">
                                </div>
                                <div class="col-8 payment-information-right">
                                    {{-- <div class="banking-group">
                                        <label for="" class="font-weight-bold">Name: </label><span class="ml-1">Truong Minh Cang</span>
                                    </div> --}}
                                    <div class="banking-group">
                                        <label for="" class="font-weight-bold">Account no: </label><span class="ml-1">007 100 24 95 915</span>
                                    </div>
                                    <div class="banking-group">
                                        <label for="" class="font-weight-bold">Branch: </label><span class="ml-1">VCB Ho Chi Minh City</span>
                                    </div>
                                    <div class="banking-group">
                                        <label for="" class="font-weight-bold">Content: </label><span class="ml-1">Payment for #{{$tnxId}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>
                  
            </div>
            <div class="col-lg-6 ">
                <label>
                    {{-- <input type="radio" name="bank" class="card-input-element d-none" value="scb"> --}}
                    <div class="card ">
                        <div class="card-body banking-information-card">
                            <div class="row ">
                                <div class="col-4">
                                    <img src="{{asset('assets/images/banking/scb.png')}}" alt="" width="200">
                                </div>
                                <div class="col-8 payment-information-right">
                                    {{-- <div class="banking-group">
                                        <label for="" class="font-weight-bold">Name: </label><span class="ml-1">Truong Minh Cang</span>
                                    </div> --}}
                                    <div class="banking-group">
                                        <label for="" class="font-weight-bold">Account no: </label><span class="ml-1">06 00 97 09 23 65</span>
                                    </div>
                                    <div class="banking-group">
                                        <label for="" class="font-weight-bold">Branch: </label><span class="ml-1">Sacombank Ho Chi Minh City</span>
                                    </div>
                                    <div class="banking-group">
                                        <label for="" class="font-weight-bold">Content: </label><span class="ml-1">Payment for #{{$tnxId}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>
                  
            </div>
            <div class="col-lg-6 ">
                <label>
                    {{-- <input type="radio" name="bank" class="card-input-element d-none" value="tcb"> --}}
                    <div class="card ">
                        <div class="card-body banking-information-card">
                            <div class="row ">
                                <div class="col-4">
                                    <img src="{{asset('assets/images/banking/tcb.png')}}" alt="" width="200">
                                </div>
                                <div class="col-8 payment-information-right">
                                    {{-- <div class="banking-group">
                                        <label for="" class="font-weight-bold">Name: </label><span class="ml-1">Truong Minh Cang</span>
                                    </div> --}}
                                    <div class="banking-group">
                                        <label for="" class="font-weight-bold">Account no: </label><span class="ml-1">190 30 40 16 48 019</span>
                                    </div>
                                    <div class="banking-group">
                                        <label for="" class="font-weight-bold">Branch: </label><span class="ml-1">Techcombank Ho Chi Minh City</span>
                                    </div>
                                    <div class="banking-group">
                                        <label for="" class="font-weight-bold">Content: </label><span class="ml-1">Payment for #{{$tnxId}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>
                  
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    {{-- <button type="button" class="btn btn-success d-flex justify-content-around align-items-center" onclick="sendPaymentMethod()"><i class="ti ti-email"></i> Send request</button> --}}
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>


<script>
    var sendPaymentMethod = () => {
        var bank = $('input[name="bank"]:checked').val()
        console.log(bank)
    }
</script>