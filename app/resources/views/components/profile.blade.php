<div class="row">
    <div class="col-lg-3 col-md-4 mb-sm-2">
        <!-- Profile Image -->
        @include('core.profile.avatar')
    </div>
    <div class="col-md-8 col-lg-9">
        <div class="nav-tabs-custom">
            @include('core.profile.profile_nav')
            <div class="card lf-toggle-bg-card lf-toggle-border-color border-top-0">
                <div class="card-body lf-toggle-text-color py-4">
                    {{ $slot }}
                    @isset($button)
                        <div class="py-3 px-2">
                            {{ $button }}
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
