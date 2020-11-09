<div class="d-flex justify-content-between font-weight-bold mb-4">
    <span class="font-size-14">{{ __('Sell') }} <span v-text="pairDetail.tradeCoin"></span></span>
    @auth
    <span class="font-size-10 text-right"><span v-text="user.tradeCoinBalance"></span> <span v-text="pairDetail.tradeCoin"></span></span>
    @endauth
</div>
