<section class="tm-feature section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-6 d-flex">
                <div class="m-auto">
                    @component('regular_pages.components.section_title', ['align' => 'left'])
                        {{ __("What is Bitcoin Currency") }}
                        @slot('subtite')
                            {{ __('Bitcoin is a cryptocurrency created in 2009. Marketplaces called “bitcoin exchanges” allow people to buy or sell bitcoins using different currencies.') }}
                        @endslot
                    @endcomponent
                </div>
            </div>
            <div class="col-md-5 d-flex offset-md-1">
                <div class="m-auto">
                    <img src="{{ get_regular_site_image('cryptocurrency.png') }}"
                         alt="bitcoin"
                         class="img-fluid">
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-4 col-md-6 col-sm-12 my-3 d-flex">
                <div class="lf-w-100px">
                    @include('regular_pages.svg.wallet')
                </div>
                <div class="ml-3">
                    <h4 class="mt-0">{{ __('Security Wallet') }}</h4>
                    <p>Buy, store, exchange & earn crypto. Join 5 million+ people using VIKIONE Wallet.</p>
                    <p><a href="#"
                          class="btn btn-sm btn-danger">{{ __('Visit Now') }}</a></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 my-3 d-flex">
                <div class="lf-w-100px">
                    @include('regular_pages.svg.bitcoin')
                </div>
                <div class="ml-3">
                    <h4 class="mt-0">{{ __('Instant Exchange') }}</h4>
                    <p>The best place to purchase bitcoin, ethereum and altcoins instantly.</p>
                    <p><a href="#"
                          class="btn btn-sm btn-danger">{{ __('Visit Now') }}</a></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 my-3 d-flex">
                <div class="lf-w-100px">
                    @include('regular_pages.svg.support')
                </div>
                <div class="ml-3">
                    <h4 class="mt-0">{{ __('Expert Support') }}</h4>
                    <p>With over 18 years we are still here because we are the real true Experts! Period!</p>
                    <p><a href="#"
                          class="btn btn-sm btn-danger">{{ __('Visit Now') }}</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
