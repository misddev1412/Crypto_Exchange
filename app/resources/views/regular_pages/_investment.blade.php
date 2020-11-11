<section class="tm-investment section-padding bg-black">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                @component('regular_pages.components.section_title', ['align' => 'center'])
                    <span class='text-danger'>C</span>urrency {{ __("Investment") }}
                    @slot('subtite')
                        {{ __('Instant access to investing, anytime and anywhere. Investing has never been easier. Everything you are looking for in an ultimate investment platform — on the device of your choice.') }}
                    @endslot
                @endcomponent
            </div>
        </div>

        <div class="row justify-content-center">
            <!-- item -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="tm-investment-card card lf-toggle-border-color bg-secondary ">
                    <div class="card-header p-0">
                        <div class="card-img">
                            <img src="{{ get_regular_site_image('img-1.jpg') }}"
                                 alt="" class="img-fluid">
                        </div>
                    </div>
                    <div class="card-body position-relative text-center">
                        <div class="card-icon">
                            <i class="fa fa-bitcoin bg-danger tm-investment-card-icon"></i>
                        </div>
                        <div class="content mt-4">
                            <h4 class="title font-size-22">{{ __('Bitcoin Transaction') }}</h4>
                            <p>{{ __('Bitcoin (BTC) was created to function as peer-to-peer electronic cash. Whether you are spending or accepting BTC as payment it is prudent to understand how a transaction works') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- item -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="tm-investment-card card lf-toggle-border-color bg-secondary">
                    <div class="card-header p-0">
                        <div class="card-img">
                            <img src="{{ get_regular_site_image('img-2.jpeg') }}"
                                 alt="" class="img-fluid">
                        </div>
                    </div>
                    <div class="card-body position-relative text-center">
                        <div class="card-icon">
                            <i class="fa fa-dollar bg-danger tm-investment-card-icon"></i>
                        </div>
                        <div class="content mt-4">
                            <h4 class="title font-size-22">{{ __('Dollar Investment') }}</h4>
                            <p>{{ __('The Dollar Investment Management Account arrangement is ideal for high networth individuals looking for higher yields compared to traditional bank deposits but do not have the time to manage their own funds or have limited investment options accessible to them.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- item -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="tm-investment-card card lf-toggle-border-color bg-secondary">
                    <div class="card-header p-0">
                        <div class="card-img">
                            <img src="{{ get_regular_site_image('img-3.jpg') }}"
                                 alt="" class="img-fluid">
                        </div>
                    </div>
                    <div class="card-body position-relative text-center">
                        <div class="card-icon">
                            <i class="fa fa-euro bg-danger tm-investment-card-icon"></i>
                        </div>
                        <div class="content mt-4">
                            <h4 class="title font-size-22">{{ __('Euro Exchange') }}</h4>
                            <p>{{ __('InforEuro provides the European Commission’s official monthly accounting rates for the euro, the corresponding conversion rates for other currencies and historic conversion rates from 1994.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
