<a href="{{ route('user.token') }}" class="modal-close" data-dismiss="modal"><em class="ti ti-close"></em></a>
<div class="popup-body text-center">
    <div class="gaps-2x"></div>
    <div class="pay-status pay-status-success">
        <em class="ti ti-check warning"></em>
    </div>
    <div class="gaps-2x"></div>
    <h3>{{__("We're reviewing your payment.")}}</h3>
    <p>{{__("We'll review your transaction and get back to your within 6 hours. You'll receive an email with the details of your contribution.")}}</p>
    <div class="gaps-2x"></div>
    <a href="{{ route('user.transactions') }}" class="btn btn-primary">{{ __('View Transaction') }}</a>
    <div class="gaps-1x"></div>
</div>
