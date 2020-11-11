@extends('layouts.admin')
@section('title', 'Product Register')
@section('content')

<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="main-content col-lg-12">
                @if (session('thanks'))
                <div class="alert alert-dismissible fade show alert-warning" role="alert">
                    <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&nbsp;</a>
                    {!! session('thanks') !!}
                </div>
                @endif
                <div class="content-area card">
                    <div class="card-innr">
                        <div class="card-head wide-max-lg pb-0">
                            <h4 class="card-title card-title-lg">Register the Product</h4>
                            <p class="mt-2">TokenLite is now installed and ready to use. <strong>Your application must be registered to unlock all the features and activate the app.</strong> Please follow the instruction below to provide your purchase code and register the application.</p>
                            <p>Contact our <strong><a href="https://www.templaterex.com/contact/" target="_blank">support team</a></strong>, if you need any kind of help. 
                                <br>Check out <a href="{{ route('admin.system') }}">application system information</a>. We hope you enjoy it!</p>
                        </div>
                        <div class="sap sap-gap"></div>
                        <div class="card-text">
                            <div class="row guttar-50px guttar-vr-30px">
                                <div class="col-lg-4 order-lg-last">
                                    <p class="alert alert-danger fs-13"><strong>Important:</strong>This Script is protected by copyright. <a href="https://www.templaterex.com/terms/#license" target="_blank">Template Rex</a> provides this script for personal tests, we do not host any files that violate copyright. All digital products on the website are released under GNU General Public License and designed by one or more third parties (developers).
                                   These products and any other material presented on the website are available for personal use only. If you want to use the products for commercial (business) purposes, you should buy them directly from the developers.
                                   We do not provide any license or trial / commercial keys needed for automatic updating of these products. All products presented on the website operate in full and on an unlimited number of websites (domains).</p>
                                    <div class="card pd-2x mb-0 bg-light rounded">
                                        <p class="text-head">Following data is sent to Template Rex server to ensure that purchase code is valid with your install &amp; activate the application.</p>
                                        <table class="table fs-12">
                                            <tr>
                                                <td width="120">Registration Info:</td>
                                                <td>Purchase Code, <br>Username & Email</td>
                                            </tr>
                                            <tr>
                                                <td>Site/App Name:</td>
                                                <td><span class="text-wrap wide-120px">{{ base64_encode(site_info('name')) }}</span></td>
                                            </tr>
                                            <tr>
                                                <td>Site/App URL: </td>
                                                <td>
                                                    <span class="text-wrap wide-120px">{{ site_info('url_only') }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Installed Version:</td>
                                                <td>{{ app_info('version'). ' / ' .app_info('key') }}</td>
                                            </tr>
                                        </table>
                                        <p class="alert alert-warning fs-13"><em><strong>Please Note:</strong> We will never collect any confidential data such as transactions, email addresses or usernames.</em></p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <h4 class="text-primary">This product is activated.</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>{{-- .card --}}
            </div>{{-- .col --}}
        </div>{{-- .container --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}

@endsection

@push('footer')
{{-- <script src="{{ asset('assets/js/public.app.js').css_js_ver() }}"></script> --}}
<script type="text/javascript">
    (function($){
        var $regpro = $('.register-product');
        $regpro.validate({
            submitHandler: function(form) {
                var $this = $(form);
                $.post($this.attr('action'), $this.serialize())
                .done(function(rs){
                    var _rs_s = (typeof rs.status != undefined && rs.status == true) ? true : false, _ms_t = (rs.msg=='success'&&_rs_s==true) ? 'success' : 'error', _ms_i = (_ms_t=='success') ? 'ti ti-unlock' : 'ti ti-lock';
                    if(rs.status == true){
                        $('.register-result').html('<div class="alert alert-'+_ms_t+'">'+rs.text+'</div>');
                        if(_rs_s){
                            setTimeout(function(){
                                window.location = "{{ route('admin.home') }}";
                            }, 5000);
                        }
                    }
                    show_toast(_ms_t, rs.message, _ms_i);
                });
            }
        });
        
    })(jQuery)
</script>
@endpush