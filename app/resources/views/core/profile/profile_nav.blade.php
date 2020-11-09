<div class="lf-nav-tab lf-toggle-border-color lf-nav-tab border-top border-right border-left">
    <a class="nav-link border-left-0 {{ is_current_route(['profile.index','profile.edit'],'active') }}"
       href="{{ route('profile.index') }}">{{ __('Profile') }}</a>
    <a class="nav-link {{ is_current_route(['preference.index','preference.edit'],'active') }}"
       href="{{ route('preference.index') }}">{{ __('Preference') }}</a>
    <a class="nav-link {{ is_current_route('profile.change-password','active') }}"
       href="{{ route('profile.change-password') }}">{{ __('Change Password') }}</a>
    <a class="nav-link {{ is_current_route('profile.google-2fa.create') }}"
       href="{{ route('profile.google-2fa.create') }}">{{ __('Google 2FA') }}</a>
    <a class="nav-link {{ is_current_route('kyc-verifications.index') }}"
       href="{{ route('kyc-verifications.index') }}">{{ __('KYC Verification') }}</a>
    <a class="nav-link {{ is_current_route(['referral.link.show'],'active') }}"
       href="{{ route('referral.link.show') }}">{{ __('Referral Link') }}</a>
</div>
