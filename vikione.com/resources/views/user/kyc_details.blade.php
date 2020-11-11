@extends('layouts.user')
@section('title', __('KYC Details'))

@section('content')
@include('layouts.messages')
<div class="content-area card">
        <div class="card-innr">
            <div class="card-head d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">KYC Application Documents</h4>
                    <div class="d-flex align-items-center guttar-20px">
                        <div class="flex-col d-sm-block d-none">
                            <a href="{{ URL::previous() }}" class="btn btn-light btn-sm btn-auto btn-primary"><em class="fas fa-arrow-left mr-3"></em>Back</a>
                        </div>
                        <div class="flex-col d-sm-none">
                            <a href="{{ URL::previous() }}" class="btn btn-light btn-icon btn-sm btn-primary"><em class="fas fa-arrow-left"></em></a>
                        </div>
                    </div>
                </div>
                <div class="gaps-1-5x"></div>
                <div class="data-details d-md-flex flex-wrap align-items-center justify-content-between">
                    <div class="fake-class">
                        <span class="data-details-title">Submited By</span>
                        <span class="data-details-info">{{ $kyc->firstName.' '.$kyc->lastName }}</span>
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
                        <span class="badge badge-md badge-block badge-{{ __status($kyc->status,'status')}} ucap">{{ __status($kyc->status,'text') }}</span>
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
                        <div class="data-details-des">{{ $kyc->firstName }}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Last Name</div>
                        <div class="data-details-des">{{ $kyc->lastName }}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Email Address</div>
                        <div class="data-details-des">{{ $kyc->email }}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Phone Number</div>
                        <div class="data-details-des">{{ $kyc->phone }}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Date of Birth</div>
                        <div class="data-details-des">{{ _date($kyc->dob, get_setting('site_date_format')) }}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Full Address</div>
                        <div class="data-details-des">{{ $kyc->address1 }}, {{ $kyc->address2 }}, {{ $kyc->city }}, {{ $kyc->state }} {{ $kyc->zip }}.</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Country of Residence</div>
                        <div class="data-details-des">{{ $kyc->country }}</div>
                    </li>{{-- li --}}
                     <li>
                        <div class="data-details-head">Wallet Type</div>
                        <div class="data-details-des">{{ $kyc->walletName }}</div>
                    </li>{{-- li --}}
                     <li>
                        <div class="data-details-head">Wallet Address</div>
                        <div class="data-details-des">{{ $kyc->walletAddress }}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Telegram Username</div>
                        <div class="data-details-des"><span{{ '@'.preg_replace('/@/', '', $kyc->telegram, 1) }} </span><a href="https://t.me/{{preg_replace('/@/', '', $kyc->telegram, 1)}}" target="_blank"><em class="far fa-paper-plane"></em></a></div>
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
                        @if($kyc->document != NULL)
                        <ul class="data-details-docs">
                            @if($kyc->document != NULL)
                            <li>
                                <span class="data-details-docs-title">{{ $kyc->documentType == 'nidcard' ? 'Front Side' : 'Document' }}</span>
                                <div class="data-doc-item data-doc-item-lg">
                                    <div class="data-doc-image">
                                        @if(pathinfo(storage_path('app/'.$kyc->document), PATHINFO_EXTENSION) == 'pdf')
                                        <em class="kyc-file fas fa-file-pdf"></em>
                                        @else
                                        <img src="{{ route('user.kycs.file', ['file'=>$kyc->id, 'doc'=>1]) }}" src="">
                                        @endif
                                    </div>
                                    <ul class="data-doc-actions">
                                        <li><a href="{{ route('user.kycs.file', ['file'=>$kyc->id, 'doc'=>1]) }}" target="_blank" ><em class="ti ti-import"></em></a></li>
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
                                        <img src="{{ route('user.kycs.file', ['file'=>$kyc->id, 'doc'=>2]) }}" src="">
                                        @endif
                                    </div>
                                    <ul class="data-doc-actions">
                                        <li><a href="{{ route('user.kycs.file', ['file'=>$kyc->id, 'doc'=>2]) }}" target="_blank"><em class="ti ti-import"></em></a></li>
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
                                        <img src="{{ route('user.kycs.file', ['file'=>$kyc->id, 'doc'=>3]) }}" src="">
                                        @endif
                                    </div>
                                    <ul class="data-doc-actions">
                                        <li><a href="{{ route('user.kycs.file', ['file'=>$kyc->id, 'doc'=>3]) }}" target="_blank"><em class="ti ti-import"></em></a></li>
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
@endsection