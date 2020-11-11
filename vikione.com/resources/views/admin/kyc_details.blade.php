@extends('layouts.admin')
@section('title', 'KYC details')
@php 
$space = "&nbsp;";
@endphp
@section('content')
<div class="page-content">
    <div class="container">
        <div class="card content-area">
            <div class="card-innr">
                <div class="card-head d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">KYC Information of <span>{{ _x($kyc->firstName).' '._x($kyc->lastName) }}</h4>
                    <div class="d-flex align-items-center guttar-20px">
                        <div class="flex-col d-sm-block d-none">
                            <a href="{{ route('admin.kycs') }}" class="btn btn-sm btn-auto btn-primary"><em class="fas fa-arrow-left mr-3"></em>Back</a>
                        </div>
                        <div class="flex-col d-sm-none">
                            <a href="{{ route('admin.kycs') }}" class="btn btn-icon btn-sm btn-primary"><em class="fas fa-arrow-left"></em></a>
                        </div>
                        <div class="relative d-inline-block">
                            <a href="#" class="btn btn-dark btn-sm btn-icon toggle-tigger"><em class="ti ti-more-alt"></em></a>
                            <div class="toggle-class dropdown-content dropdown-content-top-left">
                                <ul class="dropdown-list">
                                    @if($kyc->status != 'approved')
                                    <li><a class="kyc_action" href="#" data-id="{{ $kyc->id }}" data-toggle="modal" data-target="#actionkyc"><em class="far fa-check-square"></em>Approve</a></li>
                                    @endif
                                    @if($kyc->status != 'rejected')
                                    <li><a href="javascript:void(0)" data-current="{{ __status($kyc->status,'status') }}" data-id="{{ $kyc->id }}" class="kyc_reject"><em class="fas fa-ban"></em>Reject</a></li>
                                    @endif
                                    @if($kyc->status == 'missing' || $kyc->status == 'rejected')
                                    <li><a href="javascript:void(0)" data-id="{{ $kyc->id }}" class="kyc_delete"><em class="fas fa-trash-alt"></em>Delete</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="gaps-1-5x"></div>
                <div class="data-details d-md-flex flex-wrap align-items-center justify-content-between">
                    <div class="fake-class">
                        <span class="data-details-title">Submited By</span>
                        <span class="data-details-info">{{ set_id($kyc->userId) }}</span>
                    </div>
                    <div class="fake-class">
                        <span class="data-details-title">Submited On</span>
                        <span class="data-details-info">{{ _date($kyc->created_at) }}</span>
                    </div>
                    @if($kyc->reviewedBy != 0)
                    <div class="fake-class">
                        <span class="data-details-title">Checked By</span>
                        <span class="data-details-info">{{ $kyc->checker_info->name }}</span>
                    </div>
                    @else
                    <div class="fake-class">
                        <span class="data-details-title">Checked On</span>
                        <span class="data-details-info">Not reviewed yet</span>
                    </div>
                    @endif
                    @if($kyc->reviewedAt != NULL)
                    <div class="fake-class">
                        <span class="data-details-title">Checked On</span>
                        <span class="data-details-info">{{ _date($kyc->updated_at) }}</span>
                    </div>
                    @endif
                    <div class="fake-class">
                        <span class="badge badge-md badge-{{ __status($kyc->status,'status')}} ucap">{{ __status($kyc->status,'text') }}</span>
                    </div>
                    @if($kyc->notes !== NULL)
                    <div class="gaps-2x w-100 d-none d-md-block"></div>
                    <div class="w-100">
                        <span class="data-details-title">Admin Note</span>
                        <span class="data-details-info">{!! $kyc->notes !!}</span>
                    </div>
                    @endif
                </div>
                <div class="gaps-3x"></div>
                <h6 class="card-sub-title">Personal Information</h6>
                <ul class="data-details-list">
                    <li>
                        <div class="data-details-head">First Name</div>
                        <div class="data-details-des">{!! ($kyc->firstName) ? _x($kyc->firstName) : $space !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Last Name</div>
                        <div class="data-details-des">{!! ($kyc->lastName) ? _x($kyc->lastName) : $space !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Email Address</div>
                        <div class="data-details-des">{!! ($kyc->email) ? explode_user_for_demo($kyc->email, auth()->user()->type) : $space !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Phone Number</div>
                        <div class="data-details-des">{!! ($kyc->phone) ? _x($kyc->phone) : $space !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Date of Birth</div>
                        <div class="data-details-des">{!! ($kyc->dob) ? _date($kyc->dob, get_setting('site_date_format')) : $space !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Full Address</div>
                        <div class="data-details-des">{!! kyc_address($kyc, $space) !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Country of Residence</div>
                        <div class="data-details-des">{!! ($kyc->country) ? _x($kyc->country) : $space !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Wallet Type</div>
                        <div class="data-details-des">{!! ($kyc->walletName) ? _x($kyc->walletName) : $space !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Wallet Address</div>
                        <div class="data-details-des">{!! ($kyc->walletAddress) ? _x($kyc->walletAddress) : $space !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Telegram Username</div>
                        <div class="data-details-des">
                            @if ($kyc->telegram)
                            <span>{{ '@'.preg_replace('/@/', '', _x($kyc->telegram), 1) }}</span><a href="https://t.me/{{preg_replace('/@/', '', _x($kyc->telegram), 1)}}" target="_blank"><em class="far fa-paper-plane"></em></a>
                            @else 
                            &nbsp;
                            @endif
                        </div>
                    </li>{{-- li --}}
                </ul>
                <div class="gaps-3x"></div>
                <h6 class="card-sub-title">Uploaded Documnets</h6>
                <ul class="data-details-list">
                    <li>
                        <div class="data-details-head">
                            @if($kyc->documentType == 'nidcard')
                            National ID Card
                            @elseif($kyc->documentType == 'passport')
                            Passport
                            @elseif($kyc->documentType == 'license')
                            Driving License
                            @else
                            Documents
                            @endif
                        </div>
                        @if($kyc->document != NULL||$kyc->document2 != NULL||$kyc->document3 != NULL)
                        <ul class="data-details-docs">
                            @if($kyc->document != NULL)
                            <li>
                                <span class="data-details-docs-title">{{ $kyc->documentType == 'nidcard' ? 'Front Side' : 'Document' }}</span>
                                <div class="data-doc-item data-doc-item-lg">
                                    <div class="data-doc-image">
                                        @if(pathinfo(storage_path('app/'.$kyc->document), PATHINFO_EXTENSION) == 'pdf')
                                        <em class="kyc-file fas fa-file-pdf"></em>
                                        @else
                                        <img src="{{ route('admin.kycs.file', ['file'=>$kyc->id, 'doc'=>1]) }}" src="">
                                        @endif
                                    </div>
                                    <ul class="data-doc-actions">
                                        <li><a href="{{ route('admin.kycs.file', ['file'=>$kyc->id, 'doc'=>1]) }}" target="_blank" ><em class="ti ti-import"></em></a></li>
                                    </ul>
                                </div>
                            </li>{{-- li --}}
                            @endif
                            @if($kyc->document2 != NULL)
                            <li>
                                <span class="data-details-docs-title">{{ $kyc->documentType == 'nidcard' ? 'Back Side' : 'Proof' }}</span>
                                <div class="data-doc-item data-doc-item-lg">
                                    <div class="data-doc-image">
                                        @if(pathinfo(storage_path('app/'.$kyc->document2), PATHINFO_EXTENSION) == 'pdf')
                                        <em class="kyc-file fas fa-file-pdf"></em>
                                        @else
                                        <img src="{{ route('admin.kycs.file', ['file'=>$kyc->id, 'doc'=>2]) }}" src="">
                                        @endif
                                    </div>
                                    <ul class="data-doc-actions">
                                        <li><a href="{{ route('admin.kycs.file', ['file'=>$kyc->id, 'doc'=>2]) }}" target="_blank"><em class="ti ti-import"></em></a></li>
                                    </ul>
                                </div>
                            </li>{{-- li --}}
                            @endif

                            @if($kyc->document3 != NULL)
                            <li>
                                <span class="data-details-docs-title">Proof</span>
                                <div class="data-doc-item data-doc-item-lg">
                                    <div class="data-doc-image">
                                        @if(pathinfo(storage_path('app/'.$kyc->document3), PATHINFO_EXTENSION) == 'pdf')
                                        <em class="kyc-file fas fa-file-pdf"></em>
                                        @else
                                        <img src="{{ route('admin.kycs.file', ['file'=>$kyc->id, 'doc'=>3]) }}" src="">
                                        @endif
                                    </div>
                                    <ul class="data-doc-actions">
                                        <li><a href="{{ route('admin.kycs.file', ['file'=>$kyc->id, 'doc'=>3]) }}" target="_blank"><em class="ti ti-import"></em></a></li>
                                    </ul>
                                </div>
                            </li>{{-- li --}}
                            @endif
                        </ul>
                        @else 
                        No document uploaded.
                        @endif
                    </li>{{-- li --}}
                </ul>
            </div>{{-- .card-innr --}}
        </div>{{-- .card --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}

{{-- Modal End --}}
<div class="modal fade" id="actionkyc" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal"><em class="ti ti-close"></em></a>
            <div class="popup-body">
                <h3 class="popup-title">Approve the KYC Information</h3>
                <p>Please check details carefully of the application before take any action. User can not re-submit the application if you invalidated this application.</p>
                <form action="{{ route('admin.ajax.kyc.update') }}" method="POST" id="kyc_status_form">
                    @csrf
                    <input type="hidden" name="req_type" value="update_kyc_status">
                    <input type="hidden" name="kyc_id" id="kyc_id" required="required">
                    <div class="input-item input-with-label">
                        <label class="input-item-label">Admin Note</label>
                        <textarea name="notes" class="input-bordered input-textarea input-textarea-sm"></textarea>
                    </div>
                    <div class="input-item">
                        <input class="input-checkbox" id="send-email" checked type="checkbox">
                        <label for="send-email">Send Notification to Applicant</label>
                    </div>
                    <div class="gaps-1x"></div>
                    <ul class="btn-grp guttar-20px">
                        <li><button name="status" value="approved" class="form-progress-btn btn btn-md btn-primary ucap">Approve</button></li>
                        <li><button name="status" value="missing" class="form-progress-btn btn btn-md btn-light ucap">Missing</button></li>
                        <li><button name="status" value="rejected" class="form-progress-btn btn btn-md btn-danger ucap">Reject</button></li>
                    </ul>
                </form>
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>
{{-- Modal End --}}

@endsection