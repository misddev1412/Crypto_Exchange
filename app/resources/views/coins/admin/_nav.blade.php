<ul class="lf-nav-tab lf-toggle-border-color border-top border-right border-left">
    <a class="nav-link border-left-0 {{ is_current_route('coins.edit','active') }}"
       href="{{ route('coins.edit', $coin->symbol) }}">{{ __('Details') }}</a>
    <a class="nav-link {{ is_current_route('coins.api.edit','active') }}"
       href="{{ route('coins.api.edit', $coin->symbol) }}">{{ __('API') }}</a>
    <a class="nav-link {{ is_current_route('coins.deposit.edit','active') }}"
       href="{{ route('coins.deposit.edit', $coin->symbol) }}">{{ __('Deposit') }}</a>
    <a class="nav-link {{ is_current_route('coins.withdrawal.edit','active') }}"
       href="{{ route('coins.withdrawal.edit', $coin->symbol) }}">{{ __('Withdrawal') }}</a>
</ul>
