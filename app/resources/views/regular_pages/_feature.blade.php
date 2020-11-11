<section class="tm-feature section-padding bg-black">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                @component('regular_pages.components.section_title', ['align' => 'center'])
                    <span class='text-danger'>M</span> {{ __("ore than 160k+ assets supported") }}
                    @slot('subtite')
                        {{ __('More than 160k+ assets supported. Store all your coins and tokens in a single, secure wallet. The app suppports 39 blockchains and 180k+ assets.') }}
                    @endslot
                @endcomponent
            </div>
        </div>

        <div class="row justify-content-center">
            <!-- tab nav -->
            <div class="col-md-12 col-lg-8">
                <ul class="tm-tab-nav justify-content-center row nav nav-tabs border-0" role="tablist">
                    <li class="active col-sm-12 col-md-3 my-3">
                        <a data-toggle="tab" href="#tab-dashboard" class="tm-feature-tab-nav active" role="tab" aria-selected="true">
                            <div class="m-auto">
                                @include('regular_pages.svg.dashboard')
                            <h4 class="font-size-18">{{ __('Dashboard') }}</h4>
                            </div>
                        </a>
                    </li>
                    <li class="col-sm-12 col-md-3 my-3">
                        <a data-toggle="tab" href="#tab-wallet" class="tm-feature-tab-nav" role="tab" aria-selected="false">
                            <div class="m-auto">
                                @include('regular_pages.svg.wallet')
                                <h4 class="font-size-18">{{ __('Wallet') }}</h4>
                            </div>
                        </a>
                    </li>
                    <li class="col-sm-12 col-md-3 my-3">
                        <a data-toggle="tab" href="#tab-exchange" class="tm-feature-tab-nav" role="tab" aria-selected="false">
                            <div class="m-auto">
                                @include('regular_pages.svg.exchange')
                                <h4 class="font-size-18">{{ __('Exchange') }}</h4>
                            </div>
                        </a>
                    </li>
                    <li class="col-sm-12 col-md-3 my-3">
                        <a data-toggle="tab" href="#tab-chart" class="tm-feature-tab-nav" role="tab" aria-selected="false">
                            <div class="m-auto">
                                @include('regular_pages.svg.growth')
                                <h4 class="font-size-18">{{ __('Growth') }}</h4>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- tab content -->
            <div class="col-md-12 mt-3">
                <div class="tab-content">
                    <div id="tab-dashboard" class="tab-pane fade in active show border lf-toggle-border-color p-4 text-center">
                        <p class="m-0">Multi Cryptocurrency Wallet App. Securely carry your favorite cryptocurrencies on your Android and Apple devices with VIKIONE Wallet. With an average user review of 4.7 / 5.0, you can feel confident your Digital Assets are private and safe.</p>
                    </div>
                    <div id="tab-wallet" class="tab-pane fade in border lf-toggle-border-color p-4 text-center">
                        <p class="m-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam dolore eos modi, molestiae mollitia quia saepe voluptatem? A ab aperiam architecto commodi consequuntur debitis delectus, distinctio dolorem eligendi eum eveniet ex facere hic in incidunt laboriosam minima</p>
                    </div>
                    <div id="tab-exchange" class="tab-pane fade in border lf-toggle-border-color p-4 text-center">
                        <p class="m-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam dolore eos modi, molestiae mollitia quia saepe voluptatem? A ab aperiam architecto commodi consequuntur debitis delectus, distinctio dolorem eligendi eum eveniet ex facere hic in incidunt laboriosam minima</p>
                    </div>
                    <div id="tab-chart" class="tab-pane fade in border lf-toggle-border-color p-4 text-center">
                        <p class="m-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam dolore eos modi, molestiae mollitia quia saepe voluptatem? A ab aperiam architecto commodi consequuntur debitis delectus, distinctio dolorem eligendi eum eveniet ex facere hic in incidunt laboriosam minima</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
