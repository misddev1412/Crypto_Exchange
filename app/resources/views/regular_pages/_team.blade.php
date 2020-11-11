<section class="tm-eam section-padding bg-black">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                @component('regular_pages.components.section_title', ['align' => 'center'])
                    <span class='text-danger'>O</span>ur {{ __("Team") }}
                    @slot('subtite')
                        {{ __('A gathering of some of the greatest talent in their chosen field, carefully assembled to make  VIKIONE Exchange the very best news and information website serving the global blockchain and cryptocurrency community. This is our team…') }}
                    @endslot
                @endcomponent
            </div>
        </div>

        <div class="row">
            <!-- item -->
            <div class="col-lg-4 col-md-6 col-sm-12 team-item text-center">
                <div class="position-relative">
                    <img src="{{ get_regular_site_image('team-1.jpg') }}"
                         alt="team member" class="img-fluid">
                    <div class="team-content d-flex">
                        <div class="m-auto">
                            <p class="text-white">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere, vero?
                        </p>
                        <ul class="social-media-links">
                            <li>
                                <a href="#">
                                    <i class="fa fa-facebook"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-linkedin"></i>
                                </a>
                            <li>
                                <a href="#">
                                    <i class="fa fa-instagram"></i>
                                </a>
                            </li>
                        </ul>
                        </div>
                    </div>
                </div>
                <div class="team-member-name p-3 bg-danger">
                    <h3 class="font-size-22 text-white">{{ __('Marco Jon. D') }}</h3>
                </div>
            </div>
            <!-- item -->
            <div class="col-lg-4 col-md-6 col-sm-12 team-item text-center">
                <div class="position-relative">
                    <img src="{{ get_regular_site_image('team-2.jpg') }}"
                         alt="team member" class="img-fluid">
                    <div class="team-content d-flex">
                        <div class="m-auto">
                            <p class="text-white">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere, vero?
                        </p>
                        <ul class="social-media-links">
                            <li>
                                <a href="#">
                                    <i class="fa fa-facebook"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-linkedin"></i>
                                </a>
                            <li>
                                <a href="#">
                                    <i class="fa fa-instagram"></i>
                                </a>
                            </li>
                        </ul>
                        </div>
                    </div>
                </div>
                <div class="team-member-name p-3 bg-danger">
                    <h3 class="font-size-22 text-white">{{ __('Lina Marzouki') }}</h3>
                </div>
            </div>
            <!-- item -->
            <div class="col-lg-4 col-md-6 col-sm-12 team-item text-center">
                <div class="position-relative">
                    <img src="{{ get_regular_site_image('team-3.jpg') }}"
                         alt="team member" class="img-fluid">
                    <div class="team-content d-flex">
                        <div class="m-auto">
                            <p class="text-white">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere, vero?
                        </p>
                        <ul class="social-media-links">
                            <li>
                                <a href="#">
                                    <i class="fa fa-facebook"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-linkedin"></i>
                                </a>
                            <li>
                                <a href="#">
                                    <i class="fa fa-instagram"></i>
                                </a>
                            </li>
                        </ul>
                        </div>
                    </div>
                </div>
                <div class="team-member-name p-3 bg-danger">
                    <h3 class="font-size-22 text-white">{{ __('Antonio Conte') }}</h3>
                </div>
            </div>
        </div>
    </div>
</section>
