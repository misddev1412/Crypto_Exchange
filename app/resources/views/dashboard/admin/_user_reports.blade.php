<div class="position-relative min-height-475">
    <div class="cart-loader border lf-toggle-border-color lf-toggle-bg-card" v-bind:class="{hide : hideUserReportLoader}">
        <div class="lds-cart m-auto">
            <div class="lf-toggle-bg-reverse"></div>
            <div class="lf-toggle-bg-reverse"></div>
            <div class="lf-toggle-bg-reverse"></div>
        </div>
    </div>
    <div class="card lf-toggle-bg-card lf-toggle-border-color position-relative mb-2">
        <div class="card-header border-0 pt-3 pb-0 lf-toggle-border-color">
            <h4 class="font-weight-normal">{{ __('Users') }}</h4>
        </div>
        <div class="card-body py-4">
            <div class="row no-gutters">
                <div class="col-sm-7">
                    <canvas id="userChart" height="230"></canvas>
                </div>
                <div class="col-sm-5">
                    <ul class="list-group text-center text-sm-left">
                        <li class="bg-transparent py-2">
                            <i class="fa fa-circle text-info"></i> {{ __('Total') }} : @{{ totalUser }}
                        </li>
                        <li class="bg-transparent py-2">
                            <i class="fa fa-circle text-success"></i> {{ __('Active') }} : @{{ totalActiveUser }}
                        </li>
                        <li class="bg-transparent py-2">
                            <i class="fa fa-circle text-danger"></i> {{ __('Suspended') }} : @{{ totalSuspendedUser }}
                        </li>
                        <li class="bg-transparent py-2">
                            <i class="fa fa-circle text-muted"></i>  {{ __('Verified') }} : @{{ totalVerifiedUser }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
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
</div>
