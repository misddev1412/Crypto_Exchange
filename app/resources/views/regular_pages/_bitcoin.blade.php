<section class="tm-feature section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-6 d-flex">
                <div class="m-auto">
                    @component('regular_pages.components.section_title', ['align' => 'left'])
                        {{ __("What is Bitcoin Currency") }}
                        @slot('subtite')
                            {{ __('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet blanditiis doloremque dolores doloribus esse iusto laborum mollitia porro rem sapiente') }}
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
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Beatae dolore eaque.</p>
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
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Beatae dolore eaque.</p>
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
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Beatae dolore eaque.</p>
                    <p><a href="#"
                          class="btn btn-sm btn-danger">{{ __('Visit Now') }}</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
