@extends('layouts.user')
@section('title', __('User Account'))
@php($has_sidebar = true)

@section('content')
@include('layouts.messages')
<div class="content-area card">
    <div class="card-innr">
        <div class="card-head">
            <h4 class="card-title">{{__('Profile Details')}}</h4>
        </div>
        <ul class="nav nav-tabs nav-tabs-line" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#personal-data">{{__('Personal Data')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#settings">{{__('Settings')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#password">{{__('Password')}}</a>
            </li>
        </ul>{{-- .nav-tabs-line --}}
        <div class="tab-content" id="profile-details">
            <div class="tab-pane fade show active" id="personal-data">
                <form class="validate-modern" action="{{ route('user.ajax.account.update') }}" method="POST" id="nio-user-personal" autocomplete="off">
                    @csrf
                    <input type="hidden" name="action_type" value="personal_data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-item input-with-label">
                                <label for="full-name" class="input-item-label">{{__('Full Name')}}</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="text" id="full-name" name="name" required="required" placeholder="{{ __('Enter Full Name') }}" minlength="3" value="{{ $user->name }}">
                                </div>
                            </div>{{-- .input-item --}}
                        </div>
                        <div class="col-md-6">
                            <div class="input-item input-with-label">
                                <label for="email-address" class="input-item-label">{{__('Email Address')}}</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="text" id="email-address" name="email" required="required" placeholder="{{ __('Enter Email Address') }}" value="{{ $user->email }}" readonly>
                                </div>
                            </div>{{-- .input-item --}}
                        </div>
                        <div class="col-md-6">
                            <div class="input-item input-with-label">
                                <label for="mobile-number" class="input-item-label">{{__('Mobile Number')}}</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="text" id="mobile-number" name="mobile" placeholder="{{ __('Enter Mobile Number') }}" value="{{ $user->mobile }}">
                                </div>
                            </div>{{-- .input-item --}}
                        </div>
                        <div class="col-md-6">
                            <div class="input-item input-with-label">
                                <label for="date-of-birth" class="input-item-label">{{__('Date of Birth')}}</label>
                                <div class="input-wrap">
                                    <input class="input-bordered date-picker-dob" type="text" id="date-of-birth" name="dateOfBirth" required="required" placeholder="mm/dd/yyyy" value="{{ ($user->dateOfBirth != NULL ? _date($user->dateOfBirth, 'm/d/Y') : '') }}">
                                </div>
                            </div>{{-- .input-item --}}
                        </div>{{-- .col --}}
                        <div class="col-md-6">
                            <div class="input-item input-with-label">
                                <label for="nationality" class="input-item-label">{{__('Nationality')}}</label>
                                <div class="input-wrap">
                                    <select class="select-bordered select-block" name="nationality" id="nationality" required="required" data-dd-class="search-on">
                                        <option value="">{{__('Select Country')}}</option>
                                        @foreach($countries as $country)
                                        <option {{$user->nationality == $country ? 'selected ' : ''}}value="{{ $country }}">{{ $country }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>{{-- .input-item --}}
                        </div>{{-- .col --}}
                    </div>{{-- .row --}}
                    <div class="gaps-1x"></div>{{-- 10px gap --}}
                    <div class="d-sm-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary">{{__('Update Profile')}}</button>
                        <div class="gaps-2x d-sm-none"></div>
                    </div>
                </form>{{-- form --}}

            </div>{{-- .tab-pane --}}
            <div class="tab-pane fade" id="settings">
                <form class="validate-modern" action="{{ route('user.ajax.account.update') }}" method="POST" id="nio-user-settings">
                    @csrf
                    <input type="hidden" name="action_type" value="account_setting">
                    <div class="pdb-1-5x">
                        <h5 class="card-title card-title-sm text-dark">{{__('Security Settings')}}</h5>
                    </div>
                    <div class="input-item">
                        <input name="save_activity" class="input-switch input-switch-sm" type="checkbox" {{ $userMeta->save_activity == 'TRUE' ? 'checked' : '' }} id="activitylog">
                        <label for="activitylog">{{__('Save my activities log')}}</label>
                    </div>
                    <div class="input-item">
                        <input class="input-switch input-switch-sm" type="checkbox" @if($userMeta->unusual == 1) checked="" @endif name="unusual" id="unuact">
                        <label for="unuact">{{__('Alert me by email in case of unusual activity in my account')}}</label>
                    </div>
                    <div class="gaps-1x"></div>
                    <div class="d-sm-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary">{{__('Update')}}</button>
                        <div class="gaps-2x d-sm-none"></div>
                    </div>
                </form>
            </div>{{-- .tab-pane --}}

            <div class="tab-pane fade" id="password">
                <form class="validate-modern" action="{{ route('user.ajax.account.update') }}" method="POST" id="nio-user-password">
                    @csrf
                    <input type="hidden" name="action_type" value="pwd_change">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-item input-with-label">
                                <label for="old-pass" class="input-item-label">{{__('Old Password')}}</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="password" name="old-password" id="old-pass" required="required">
                                </div>
                            </div>{{-- .input-item --}}
                        </div>{{-- .col --}}
                    </div>{{-- .row --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-item input-with-label">
                                <label for="new-pass" class="input-item-label">{{__('New Password')}}</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" id="new-pass" type="password" name="new-password" required="required" minlength="6">
                                </div>
                            </div>{{-- .input-item --}}
                        </div>{{-- .col --}}
                        <div class="col-md-6">
                            <div class="input-item input-with-label">
                                <label for="confirm-pass" class="input-item-label">{{__('Confirm New Password')}}</label>
                                <div class="input-wrap">
                                    <input id="confirm-pass" class="input-bordered" type="password" name="re-password" data-rule-equalTo="#new-pass" data-msg-equalTo="Password not match." required="required" minlength="6">
                                </div>
                            </div>{{-- .input-item --}}
                        </div>{{-- .col --}}
                    </div>{{-- .row --}}
                    <div class="note note-plane note-info pdb-1x">
                        <em class="fas fa-info-circle"></em>
                        <p>{{__('Password should be a minimum of 6 digits and include lower and uppercase letter.')}}</p>
                    </div>
                    <div class="note note-plane note-danger pdb-2x">
                        <em class="fas fa-info-circle"></em>
                        <p>{{__('Your password will only change after your confirmation by email.')}}</p>
                    </div>
                    <div class="gaps-1x"></div>{{-- 10px gap --}}
                    <div class="d-sm-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary">{{__('Update')}}</button>
                        <div class="gaps-2x d-sm-none"></div>
                    </div>
                </form>
            </div>{{-- .tab-pane --}}
        </div>{{-- .tab-content --}}
    </div>{{-- .card-innr --}}
</div>{{-- .card --}}
<div class="content-area card">
    <div class="card-innr">
        <div class="card-head">
            <h4 class="card-title">{!! __('Two-Factor Verification') !!}</h4>
        </div>
        <p>{!! __("Two-factor authentication is a method for protection of your account. When it is activated you are required to enter not only your password, but also a special code. You can receive this code in mobile app. Even if third party gets access to your password, they still won't be able to access your account without the 2FA code.") !!}</p>
        <div class="d-sm-flex justify-content-between align-items-center pdt-1-5x">
            <span class="text-light ucap d-inline-flex align-items-center"><span class="mb-0"><small>{{ __('Current Status:') }}</small></span> <span class="badge badge-{{ $user->google2fa == 1 ? 'info' : 'disabled' }} ml-2">{{ $user->google2fa == 1 ? __('Enabled') : __('Disabled') }}</span></span>
            <div class="gaps-2x d-sm-none"></div>
            <button type="button" data-toggle="modal" data-target="#g2fa-modal" class="order-sm-first btn btn-{{ $user->google2fa == 1 ? 'warning' : 'primary' }}">{{ ($user->google2fa != 1) ? __('Enable 2FA') : __('Disable 2FA') }}</button>
        </div>
    </div>{{-- .card-innr --}}
</div>
@endsection


@push('footer')
{{-- Modal Medium --}}
<div class="modal fade" id="g2fa-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body">
                <h3 class="popup-title">{{ ($user->google2fa != 1) ? __('Enable') : __('Disable') }} {{ __('2FA Authentication') }}</h3>
                <form class="validate-modern" action="{{ route('user.ajax.account.update') }}" method="POST" id="nio-user-2fa">
                    @csrf
                    <input type="hidden" name="action_type" value="google2fa_setup">
                    @if($user->google2fa != 1)
                    <div class="pdb-1-5x">
                        <p><strong>{{ __('Step 1:') }}</strong> {{ __('Install this app from') }} <a target="_blank" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2">{{ __('Google Play') }} </a> {{ __('store or') }} <a target="_blank" href="https://itunes.apple.com/us/app/google-authenticator/id388497605">{{ __('App Store') }}</a>.</p>
                        <p><strong>{{ __('Step 2:') }}</strong> {{ __('Scan the below QR code by your Google Authenticator app, or you can add account manually.') }}</p>
                        <p><strong>{{ __('Manually add Account:') }}</strong><br>{{ __('Account Name:') }} <strong class="text-head">{{ site_info() }}</strong> <br> {{ __('Key:') }} <strong class="text-head">{{ $google2fa_secret }}</strong></p>
                        <div class="row g2fa-box">
                            <div class="col-md-4">
                                <img class="img-thumbnail" src="{{ route('public.qrgen', ['text' => $google2fa]) }}" alt="">
                            </div>
                            <div class="col-md-8">
                                <div class="input-item">
                                    <label for="google2fa_code">{{ __('Enter Google Authenticator Code') }}</label>
                                    <input id="google2fa_code" type="number" class="input-bordered" name="google2fa_code" placeholder="{{ __('Enter the Code to verify') }}">
                                </div>
                                <input type="hidden" name="google2fa_secret" value="{{ $google2fa_secret }}">
                                <input name="google2fa" type="hidden" value="1">
                                <button type="submit" class="btn btn-primary">{{ __('Confirm 2FA') }}</button>
                            </div>
                        </div>
                        <div class="gaps-2x"></div>
                        <p class="text-danger"><strong>{{ __('Note:') }}</strong> {{ __('If you lost your phone or uninstall the Google Authenticator app, then you will lost access of your account.') }}</p>
                    </div>
                    @else
                    <div class="pdb-1-5x">
                        <div class="input-item">
                            <label for="google2fa_code">{{ __('Enter Google Authenticator Code') }}</label>
                            <input id="google2fa_code" type="number" class="input-bordered" name="google2fa_code" placeholder="{{ __("Enter the Code to verify") }}">
                        </div>
                        <input name="google2fa" type="hidden" value="0">
                        <button type="submit" class="btn btn-primary">{{ __('Disable 2FA') }}</button>
                    </div>
                    @endif
                </form>
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>
{{-- Modal End --}}
<script type="text/javascript">
    (function($){
        var $nio_user_2fa = $('#nio-user-2fa');
        if ($nio_user_2fa.length > 0) {
            ajax_form_submit($nio_user_2fa);
        }
    })(jQuery);
</script>
@endpush