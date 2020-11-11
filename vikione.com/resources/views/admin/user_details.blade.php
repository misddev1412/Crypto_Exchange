@extends('layouts.admin')
@section('title', 'User Details')

@section('content')
<div class="page-content">
    <div class="container">
        <div class="card content-area">
            <div class="card-innr card-innr-fix">
                <div class="card-head d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">User Details <em class="ti ti-angle-right fs-14"></em> <small class="tnx-id">{{ set_id($user->id) }}</small></h4>
                    <div class="d-flex align-items-center guttar-20px">
                        <div class="flex-col d-sm-block d-none">
                            <a href="{{ (url()->previous()) ? url()->previous() : route('admin.users') }}" class="btn btn-sm btn-auto btn-primary"><em class="fas fa-arrow-left mr-3"></em>Back</a>
                        </div>
                        <div class="flex-col d-sm-none">
                            <a href="{{route('admin.users')}}" class="btn btn-icon btn-sm btn-primary"><em class="fas fa-arrow-left"></em></a>
                        </div>
                        <div class="relative d-inline-block">
                            <a href="#" class="btn btn-dark btn-sm btn-icon toggle-tigger"><em class="ti ti-more-alt"></em></a>
                            <div class="toggle-class dropdown-content dropdown-content-top-left">
                                <ul class="dropdown-list more-menu-{{$user->id}}">
                                    <li><a class="user-email-action" href="#EmailUser" data-uid="{{ $user->id }}" data-toggle="modal"><em class="far fa-envelope"></em>Send Email</a></li>
                                    @if($user->id != save_gmeta('site_super_admin')->value)
                                    <li><a class="user-form-action user-action" href="#" data-type="reset_pwd" data-uid="{{ $user->id }}" ><em class="fas fa-shield-alt"></em>Reset Pass</a></li>
                                    @endif
                                    @if(Auth::id() != $user->id && $user->id != save_gmeta('site_super_admin')->value)
                                    @if($user->status != 'suspend')
                                    <li><a href="#" data-uid="{{ $user->id }}" data-type="suspend_user" class="user-action"><em class="fas fa-ban"></em>Suspend</a></li>

                                    @else
                                    <li><a href="#" data-uid="{{ $user->id }}" data-type="active_user" class="user-action"><em class="fas fa-ban"></em>Active</a></li>
                                    @endif
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="gaps-1-5x"></div>
                <div class="data-details d-md-flex">
                    <div class="fake-class">
                        <span class="data-details-title">Token Balance</span>
                        <span class="data-details-info large">{{ number_format($user->tokenBalance) }}</span>
                    </div>
					<div class="fake-class">
                        <span class="data-details-title">Point</span>
                        <span class="data-details-info large">{{ number_format($user->tokenPoint) }}</span>
                    </div>
					<div class="fake-class">
                        <span class="data-details-title">Token One</span>
                        <span class="data-details-info large">{{ number_format($user->tokenBalance2) }}</span>
                    </div>
					
                    <div class="fake-class">
                        <span class="data-details-title">Contributed</span>
                        <span class="data-details-info large">{{ number_format($user->contributed) }} <small>USD</small></span>
                    </div>
                    <div class="status_user fake-class">
                        <span class="data-details-title">User Status</span>
                        <span class="badge badge-{{ __status($user->status, 'status' ) }} ucap">{{ $user->status }}</span>
                    </div>
                    <ul class="data-vr-list">
                        <li><div class="data-state data-state-sm data-state-{{ $user->email_verified_at !== null ? 'approved' : 'pending'}}"></div> Email</li>
                        @php
                        if(isset($user->kyc_info->status)){
                            $user->kyc_info->status = str_replace('rejected', 'canceled',$user->kyc_info->status);
                        }
                        @endphp
                        @if($user->role != 'admin')
                        <li><div class="data-state data-state-sm data-state-{{ !empty($user->kyc_info) ? $user->kyc_info->status : 'missing' }}"></div> KYC</li>
                        @endif
                    </ul>
                </div>
                <div class="gaps-3x"></div>
                <h6 class="card-sub-title">User Information</h6>
                <ul class="data-details-list">
                    <li>
                        <div class="data-details-head">Full Name</div>
                        <div class="data-details-des">{!! $user->name ? $user->name : '&nbsp;' !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Email Address</div>
                        <div class="data-details-des">{!! explode_user_for_demo($user->email, auth()->user()->type) !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Mobile Number</div>
                        <div class="data-details-des">{!! $user->mobile ? $user->mobile : '&nbsp;' !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Date of Birth</div>
                        <div class="data-details-des">{!! $user->dateOfBirth ? _date($user->dateOfBirth) : '&nbsp;' !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Nationality</div>
                        <div class="data-details-des">{!! $user->nationality ? $user->nationality : '&nbsp;' !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Wallet Address</div>
                        <div class="data-details-des">
                            <span>
                                {!! $user->walletAddress ? $user->walletAddress : '<small class="text-light">Not added yet!</small>' !!} 
                                {!! ($user->walletType) ? "<small>(".ucfirst($user->walletType)." Wallet)</small>" : '' !!}
                            </span>
                        </div>
                    </li>{{-- li --}}
                </ul>
                <div class="gaps-3x"></div>
                <h6 class="card-sub-title">More Information</h6>
                <ul class="data-details-list">
                    <li>
                        <div class="data-details-head">Joining Date</div>
                        <div class="data-details-des">{!! $user->created_at ? _date($user->created_at) : '&nbsp;' !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Referred By</div>
                        <div class="data-details-des">{!! ($user->referral != NULL && !empty($user->referee->name) ? '<span>'.$user->referee->name.' <small>('.set_id($user->referral).')</small></span>' : '<small class="text-light">Join without referral!</small>') !!}</div>
                    </li>{{-- li --}}
                    @if(isset($refered) && $refered && count($refered) > 0)
                    <li>
                        <div class="data-details-head">Total Referred</div>
                        <div class="data-details-des">{!! count($refered).' Contributors' !!}</div>
                    </li>{{-- li --}}
                    @endif
                    <li>
                        <div class="data-details-head">Reg Method</div>
                        <div class="data-details-des">{!! $user->registerMethod ? ucfirst($user->registerMethod) : '&nbsp;' !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">2FA Enabled</div>
                        <div class="data-details-des">{!! $user->google2fa==1 ? 'Yes' : 'No' !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Affiliate</div>
                        <div class="data-details-des">
                            @switch($user->affiliate)
                            @case('normal')
                               
                                    <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger" data-affiliate="normal">
                                        Normal
                                    </a>
                                
                                @break
                            @case('silver')
                            
                                <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger" data-affiliate="silver">
                                    Silver
                                </a>
                           
                                @break
                            @case('gold')
                            
                            <a href="#" class="btn btn-warning-alt btn-xs btn-icon toggle-tigger" data-affiliate="gold">
                              Gold
                          </a>
                                @break
                            @case('platinum')
                           
                                <a href="#" class="btn btn-danger-alt btn-xs btn-icon toggle-tigger" data-affiliate="platinum">
                                  Platinum
                              </a>
                                @break
                            @case('diamond')
                            <a href="#" class="btn btn-success-alt btn-xs btn-icon toggle-tigger" data-affiliate="diamond">
                              Diamond
                          </a>
                                @break
                            @default
                                
                                    <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger" data-affiliate="normal">
                                        Normal
                                    </a>
                        @endswitch
                        </div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Number of floors to receive affiliate</div>
                    <div class="data-details-des"> {!! $floor !!}F
                        </div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Last Login</div>
                        <div class="data-details-des">{!! $user->lastLogin && $user->email_verified_at !== null ? _date($user->lastLogin) : '<small class="text-light">Not logged yet!</small>' !!}</div>
                    </li>{{-- li --}}
                </ul>
            </div>{{-- .card-innr --}}
        </div>{{-- .card --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}

{{-- PWD Email Modal --}}
<div class="modal fade" id="EmailUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-lg modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-lg">
                <h3 class="popup-title">Send Email to User </h3>
                <div class="msg-box"></div>
                <form id="emailToUser" action="{{ route('admin.ajax.users.email') }}" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="input-item input-with-label">
                        <label class="clear input-item-label">Subject</label>
                        <input type="text" name="subject" class="input-bordered cls " placeholder="Email Subject">
                        <span class="input-note">If blank It's will replace with default from EMail Template</span>
                    </div>
                    <div class="input-item input-with-label">
                        <label class="clear input-item-label">Greeting</label>
                        <input type="text" name="greeting" class="input-bordered cls " placeholder="Email Greeting">
                        <span class="input-note">If blank It's will replace with default from EMail Template</span>
                    </div>
                    <div class="input-item input-with-label">
                        <label class="clear input-item-label">Message</label>
                        <textarea required="required" name="message" class="input-bordered cls input-textarea input-textarea-sm" type="text" placeholder="Write something..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Send</button>
                </form>
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>
@endsection
