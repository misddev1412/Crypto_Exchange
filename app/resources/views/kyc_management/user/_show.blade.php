<div class="border-bottom mb-4 pb-4">
    <h4 class="text-uppercase">
        {{ __('ID Type : :idType', ['idType' => kyc_type($kycVerification->type)]) }}
        <span class="ml-3 badge badge-{{ config('commonconfig.kyc_status.' . $kycVerification->status . '.color_class') }}">{{ kyc_status($kycVerification->status) }}</span>
    </h4>

    @if($kycVerification->status == STATUS_PENDING)
        <p class="text-muted my-3">{{ __('Your KYC verification request is being reviewed. It will take maximum 3 business day to approve / decline your request.') }}</p>
    @endif

    @if(!is_null($kycVerification->reason))
        <div class="form-group row">
            <label class="col-sm-4 font-weight-bold">{{ __('Reason') }}</label>
            <div class="col-sm-8">
                <p class="form-control-static">
                    {{ $kycVerification->reason }}
                </p>
            </div>
        </div>
    @endif
</div>

@include('kyc_management.admin._show', ['user' => $kycVerification])
