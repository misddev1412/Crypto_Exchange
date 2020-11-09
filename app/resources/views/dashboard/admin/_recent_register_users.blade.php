<div class="position-relative min-height-405">
    <div class="cart-loader border lf-toggle-border-color lf-toggle-bg-card" v-bind:class="{hide : hideRecentRegisterUserLoader}">
        <div class="lds-cart m-auto">
            <div class="lf-toggle-bg-reverse"></div>
            <div class="lf-toggle-bg-reverse"></div>
            <div class="lf-toggle-bg-reverse"></div>
        </div>
    </div>
    <div class="card lf-toggle-bg-card lf-toggle-border-color position-relative mb-2">
        <div class="card-header border-0 pt-3 pb-0 lf-toggle-border-color">
            <h4 class="font-weight-normal">{{ __('Recent Joined Users') }}</h4>
            @if(has_permission('admin.users.index'))
                <button type="button"
                        class="box-action-btn dropdown-toggle"
                        data-toggle="dropdown"
                        aria-expanded="false">
                    <i class="fa fa-ellipsis-v"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right text-center" role="menu">
                    <a href="{{ route('admin.users.index') }}" class="dropdown-item small"><i class="fa fa-users"></i> {{ __('View All Users') }}</a>
                </div>
            @endif
        </div>
        <div class="card-body pt-2 pb-3">
            <div class="table-responsive-sm pb-1" v-html="recentUserListView"></div>
        </div>
    </div>
</div>
