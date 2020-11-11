@extends('layouts.admin')
@section('title', 'Admin Profile')

@section('content')
<div class="page-content">
    <div class="container">
        @include('layouts.messages')
        @include('vendor.notice')
        <div class="row">
            <div class="main-content col-lg-12">
                <div class="content-area card">
                    <div class="card-innr">
                        <div class="card-head">
                            <h4 class="card-title">{{__('Profile Details')}}</h4>
                        </div>
                        <div class="nav nav-tabs nav-tabs-line">
                            <ul class="nav mb-0" id="myTab" role="tablist">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#accountInfo">My Profile</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#secutity">Secutity Settings</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#changePassword">Change Password</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.profile.activity') }}">Activity</a></li>
                            </ul>
                        </div>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="accountInfo">
                                <div class="w-xl-16x">
                                    <form action="{{ route('admin.ajax.profile.update') }}" method="POST" id="user_account_update">
                                        @csrf
                                        <input type="hidden" name="action_type" value="personal_data">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-item input-with-label">
                                                    <label for="full-name" class="input-item-label ucap">Full Name</label>
                                                    <div class="input-wrap">
                                                        <input class="input-bordered" type="text" value="{{ $user->name }}" placeholder="Full name" id="full-name" name="name">
                                                    </div>
                                                </div>{{-- .input-item --}}
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-item input-with-label">
                                                    <label for="email-address" class="input-item-label ucap">Email Address</label>
                                                    <div class="input-wrap">
                                                        <input class="input-bordered" type="text" value="{{ $user->email }}" placeholder="Email Address" id="email-address" name="email" disabled="">
                                                    </div>
                                                </div>{{-- .input-item --}}
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-item input-with-label">
                                                    <label for="mobile-number" class="input-item-label ucap">Mobile Number</label>
                                                    <div class="input-wrap">
                                                        <input class="input-bordered" type="text" value="{{ $user->mobile }}" placeholder="Mobile Number" id="mobile-number" name="mobile">
                                                    </div>
                                                </div>{{-- .input-item --}}
                                            </div>
                                        </div>{{-- .row --}}
                                        <div class="gaps-1x"></div>
                                        <div class="d-sm-flex justify-content-between align-items-center">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div> {{-- .tab-pane --}}
                            <div class="tab-pane fade" id="secutity">
                                <div class="w-xl-16x">
                                    <h6 class="card-title card-title-sm text-dark">General Security Options</h6>
                                    <div class="gaps-2x"></div>
                                    <form action="{{ route('admin.ajax.profile.update') }}" method="POST" id="security">
                                        @csrf
                                        <input type="hidden" name="action_type" value="security">
                                        <ul class="btn-grp flex-column flex-wrap align-items-start w-100">
                                            <li class="d-flex align-items-center justify-content-between w-100">
                                                <input name="save_activity" class="input-switch input-switch-sm" type="checkbox" {{ $userMeta->save_activity == 'TRUE' ? 'checked' : '' }} id="activitylog"><label for="activitylog">Save my Activities Log.</label>
                                            </li>

                                            <li class="d-flex align-items-center justify-content-between w-100">
                                                <input name="unusual" class="input-switch  input-switch-sm" {{ $userMeta->unusual == 1 ? 'checked' : '' }} type="checkbox" id="unuact"><label for="unuact">Alert me by email for unusual activity</label>
                                            </li>
                                        </ul>
                                        <div class="gaps-3x"></div>
                                        <div class="pdb-1-5x">
                                            <h5 class="card-title card-title-sm text-dark">Manage Notification</h5>    
                                        </div>
                                        <div class="input-item">
                                            <input type="checkbox" name="notify_admin" class="input-switch input-switch-sm" id="notify_admin" {{ $userMeta->notify_admin == 1 ? 'checked' : '' }}>
                                            <label for="notify_admin">Get Notifications for all purchase</label>
                                        </div>
                                        <div class="d-sm-flex justify-content-between align-items-center">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                            <div class="gaps-2x d-sm-none"></div>
                                        </div>
                                    </form>
                                </div>
                            </div> {{-- .tab-pane --}}
                            <div class="tab-pane fade" id="changePassword">
                                <div class="w-lg-12x">
                                    <form action="{{ route('admin.ajax.profile.update') }}" method="POST" id="pwd_change" class="validate-modern">
                                        @csrf
                                        <input type="hidden" name="action_type" value="pwd_change">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="input-item input-with-label">
                                                    <label for="swalllet" class="input-item-label">Old Password</label>
                                                    <div class="input-wrap">
                                                        <input class="input-bordered" placeholder="Old Password" type="password" name="old-password" value="" required="required">
                                                    </div>
                                                </div>{{-- .input-item --}}
                                            </div>{{-- .col --}}
                                        </div>{{-- .row --}}
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="input-item input-with-label">
                                                    <label for="new-password" class="input-item-label">New Password</label>
                                                    <div class="input-wrap">
                                                        <input class="input-bordered" id="new-password" type="password" name="new-password" placeholder="New password" required="required" minlength="6">
                                                    </div>
                                                </div>{{-- .input-item --}}
                                            </div>{{-- .col --}}
                                            <div class="col-lg-6">
                                                <div class="input-item input-with-label">
                                                    <label for="date-of-birth" class="input-item-label">Confirm New Password</label>
                                                    <div class="input-wrap">
                                                        <input class="input-bordered" type="password" name="re-password" data-rule-equalTo="#new-password" placeholder="Confirm new password" data-msg-equalTo="Password didn't match." required="required" minlength="6">
                                                    </div>
                                                </div>{{-- .input-item --}}
                                            </div>{{-- .col --}}
                                        </div>{{-- .row --}}
                                        <div class="note note-plane note-info">
                                            <em class="fas fa-info-circle"></em>
                                            <p>Password should be minmum 6 character long.</p>
                                        </div>
                                        <div class="note note-plane note-danger pdb-2x">
                                            <em class="fas fa-info-circle"></em>
                                            <p>Your password will update after confirm from your email.</p>
                                        </div>
                                        <div class="gaps-1x"></div>{{-- 10px gap --}}
                                        <div class="d-sm-flex justify-content-between align-items-center">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                            <div class="gaps-2x d-sm-none"></div>
                                        </div>
                                    </form>{{-- form --}}
                                </div>
                            </div>
                        </div>{{-- .tab-content --}}
                    </div>{{-- .card-innr --}}
                </div>{{-- .card --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="content-area card">
                            <div class="card-innr">
                                <div class="card-head">
                                    <h4 class="card-title">Two-Factor Verification</h4>
                                </div>
                                <p>Two-factor authentication is a method for protection your web account. When it is activated you need to enter not only your password, but also a special code. You can receive this code by in mobile app. Even if third person will find your password, then can't access with that code.</p>
                                <div class="d-sm-flex justify-content-between align-items-center pdt-1-5x">
                                    <span class="text-light ucap d-inline-flex align-items-center"><span class="mb-0"><small>Current Status:</small></span> <span class="badge badge-{{ $user->google2fa == 1 ? 'info' : 'disabled' }} ml-2">{{ $user->google2fa == 1 ? 'Enabled' : 'Disabled' }}</span></span>
                                    <div class="gaps-2x d-sm-none"></div>
                                    <button type="button" data-toggle="modal" data-target="#g2fa-modal" class="order-sm-first btn btn-{{ $user->google2fa == 1 ? 'warning' : 'primary' }}">{{ ($user->google2fa != 1) ? 'Enable' : 'Disable' }} 2FA</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>{{-- .main-content --}}
        </div>{{-- .container --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection

@push('footer')
{{-- Modal Medium --}}
<div class="modal fade" id="g2fa-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body">
                <h3 class="popup-title">{{ ($user->google2fa != 1) ? 'Enable' : 'Disable' }} 2FA Authentication</h3>
                <form class="validate-modern" action="{{ route('admin.ajax.profile.update') }}" method="POST" id="nio-user-2fa">
                    @csrf
                    <input type="hidden" name="action_type" value="google2fa_setup">
                    @if($user->google2fa != 1)
                    <div class="pdb-1-5x">
                        <p><strong>Step 1:</strong> Install this app from <a target="_blank" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2">Google Play </a> store or <a target="_blank" href="https://itunes.apple.com/us/app/google-authenticator/id388497605">App Store</a>.</p>
                        <p><strong>Step 2:</strong> Scan the QR code by your Google Authenticator app, or you can add account manually.</p>
                        <p>Manually add Account: <br>Account Name: <strong>{{ site_info() }}</strong> <br> Key: <strong>{{ $google2fa_secret }}</strong></p>
                        <div class="row g2fa-box">
                            <div class="col-md-4">
                                <img class="img-thumbnail" src="{{ route('public.qrgen', ['text' => $google2fa]) }}" alt="">
                            </div>
                            <div class="col-md-8">
                                <div class="input-item">
                                    <label for="google2fa_code">Enter Google Authenticator Code</label>
                                    <input id="google2fa_code" type="number" class="input-bordered" name="google2fa_code" placeholder="Enter the Code to verify">
                                </div>
                                <input type="hidden" name="google2fa_secret" value="{{ $google2fa_secret }}">
                                <input name="google2fa" type="hidden" value="1">
                                <button type="submit" class="btn btn-primary">Confirm 2FA</button>
                            </div>
                        </div>
                        <div class="gaps-2x"></div>
                        <p class="text-danger"><strong>Note: </strong> If you lost your phone or Uninstall the Google Authenticator app, then you will lost access of your account.</p>
                    </div>
                    @else
                    <div class="pdb-1-5x">
                        <div class="input-item">
                            <label for="google2fa_code">Enter Google Authenticator Code</label>
                            <input id="google2fa_code" type="number" class="input-bordered" name="google2fa_code" placeholder="Enter the Code to verify">
                        </div>
                        <input name="google2fa" type="hidden" value="0">
                        <button type="submit" class="btn btn-primary">Disable 2FA</button>
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