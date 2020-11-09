@component('components.card',['type' => 'info', 'class' => 'lf-toggle-border-color lf-toggle-bg-card text-center'])
    <img src="{{ get_avatar($user->avatar) }}"
         alt="{{ __('Profile Image') }}"
         class="img-rounded img-fluid">
    <h5 class="text-bold mt-3 mb-0 text-lg text-center">{{ $user->profile->full_name }}</h5>
@endcomponent

@if(isset($walletCount) && isset($openOrderCount))
    <div class="">
        <div class="list-group">
            @if(has_permission('admin.users.wallets.index'))
                <a href="{{ route('admin.users.wallets.index', $user->id) }}"
                   class="list-group-item lf-toggle-border-color lf-toggle-bg-card d-flex justify-content-between align-items-center border-top-0 border-radius-0">
                    {{ __('Wallet') }}
                    <span class="badge badge-primary">{{ $walletCount }}</span>
                </a>
            @endif

            @if(has_permission('admin.users.open-orders.index'))
                <a href="{{ route('admin.users.open-orders.index', $user->id) }}"

                   class="list-group-item lf-toggle-border-color lf-toggle-bg-card d-flex justify-content-between align-items-center">
                    {{ __('Open Order') }}
                    <span class="badge badge-primary">{{ $openOrderCount }}</span>
                </a>
            @endif

            @if(has_permission('admin.users.trade-history.index'))
                <a href="{{ route('admin.users.trade-history.index', $user->id) }}"
                   class="list-group-item lf-toggle-border-color lf-toggle-bg-card d-flex justify-content-between align-items-center">
                    {{ __('Trade History') }}
                </a>
            @endif
        </div>
    </div>
@endif
