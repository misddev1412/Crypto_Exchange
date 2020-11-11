@if(!is_payment_method_exist() && is_super_admin())
	<a href="{{ route('admin.payments.setup') }}" class="btn btn-danger btn-between w-100 mgb-1-5x user-wallet">Important: Please setup at least one payment method to active your sale.<em class="ti ti-arrow-right"></em></a>
	<div class="gaps-1x mgb-0-5x d-lg-none d-none d-sm-block"></div>
@endif

@if(!is_mail_setting_exist() && is_super_admin())
	<a href="{{ route('admin.settings.email').'?setup=mailSetting' }}" class="btn btn-warning-alt btn-between w-100 mgb-1-5x user-wallet">Please setup your application mail settings<em class="ti ti-arrow-right"></em></a>
	<div class="gaps-1x mgb-0-5x d-lg-none d-none d-sm-block"></div>
@endif

@if(is_super_admin() && admin_notice())
<!--
	<div class="alert alert-danger-alt text-center">
        <p>Your application is registered has all the full active functions. Your copy purchased in Template Rex can be installed in multiple domains</p>
    </div>
-->
@endif