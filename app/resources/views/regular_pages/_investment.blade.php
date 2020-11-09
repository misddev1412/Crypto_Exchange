<section class="tm-investment section-padding bg-black">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                @component('regular_pages.components.section_title', ['align' => 'center'])
                    <span class='text-danger'>C</span>urrency {{ __("Investment") }}
                    @slot('subtite')
                        {{ __('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet blanditiis doloremque dolores doloribus esse iusto laborum mollitia porro rem sapiente') }}
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
                            <p>{{ __('Lorem ipsum dolor sit amet, consectetur adipisicing elit.') }}</p>
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
                            <p>{{ __('Lorem ipsum dolor sit amet, consectetur adipisicing elit.') }}</p>
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
                            <p>{{ __('Lorem ipsum dolor sit amet, consectetur adipisicing elit.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
