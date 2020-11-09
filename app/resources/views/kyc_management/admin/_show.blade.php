<div class="images"
     v-viewer>
    <div class="row">
        <div class="col-md-{{ $user->type == KYC_TYPE_PASSPORT ? '12' : '6' }}">
            <h4 class="id-header-title font-weight-normal text-uppercase">{{ __('ID Card') }} {{ $user->status == KYC_TYPE_PASSPORT ? '' : __('Front') }}</h4>
            <img src="{{ get_id_image($user->card_image['id_card_front']) }}"
                 alt="{{ __('ID Card Back') }}"
                 class="img-fluid cm-center id-image">
        </div>
        @if($user->type != KYC_TYPE_PASSPORT)
            <div class="col-md-6">
                <h4 class="id-header-title font-weight-normal text-uppercase">{{ __('ID Card Back ') }}</h4>
                <img src="{{ get_id_image($user->card_image['id_card_back']) }}"
                     alt="{{ __('ID Card Back') }}"
                     class="img-fluid cm-center id-image">
            </div>
        @endif
    </div>
</div>
