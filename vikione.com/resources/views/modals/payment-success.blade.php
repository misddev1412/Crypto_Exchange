<a href="{{ route('user.transactions') }}" class="modal-close" data-dismiss="modal"><em class="ti ti-close"></em></a>
<div class="popup-body text-center">
    <div class="gaps-2x"></div>
    <div class="pay-status pay-status-success">
        <em class="ti ti-check success"></em>
    </div>
    <div class="gaps-2x"></div>
    <h3>{{__('We’re received your payment.')}}</h3>
    <p>{{__('Thank you for your contribution, we are added the token to your account balance.')}}</p>
    <div class="gaps-2x"></div>
    <a href="{{ route('user.transactions') }}" class="btn btn-primary">{{__('View Transaction')}}</a>
    <div class="gaps-1x"></div>
</div>
