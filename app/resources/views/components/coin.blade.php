<div class="row">
    <div class="col-md-3 text-center text-light">
        <!-- Icon -->
        @include('coins.admin.info')
    </div>
    <div class="col-md-9">
        <div class="nav-tabs-custom">
            @include('coins.admin._nav')
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
