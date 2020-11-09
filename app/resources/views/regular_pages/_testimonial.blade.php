<section class="tm-feature section-padding">
    <div class="container">
        <div id="tm-testimonial">
            <div class="row" v-if="totalTestimonial > 0">
                <div class="col-md-4 col-sm-12 text-center">
                    <div class="img lf-w-200px lf-h-200px rounded-circle overflow-hidden border-3 border-danger m-auto">
                        <img v-bind:src="activeTestimonial.avatar"
                             alt="client avatar" class="tm-testimonial-img">
                    </div>
                    <h5 class="text-danger font-size-20 mb-0 mt-3">@{{ activeTestimonial.client }}</h5>
                    <p class="font-size-18">@{{ activeTestimonial.clientRole }}</p>
                </div>
                <div class="col-md-8 col-sm-12">
                    <blockquote class="blockquote d-flex">
                        <i class="fa fa-quote-left text-danger"></i>
                        <div class="ml-3 pt-3 mt-md-3 mt-0">
                            <p>
                                <i>@{{ activeTestimonial.message }}</i>
                            </p>
                            <div class="tm-btn-group mt-4" v-if="totalTestimonial > 1">
                                <button class="btn btn-sm font-size-18 btn-danger" v-on:click="prev()">
                                    <i class="fa fa-long-arrow-left"></i>
                                </button>
                                <button class="btn btn-sm font-size-18 btn-danger" v-on:click="next()">
                                    <i class="fa fa-long-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </blockquote>
                </div>
            </div>
        </div>
    </div>
</section>
