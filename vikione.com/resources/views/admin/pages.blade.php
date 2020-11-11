@extends('layouts.admin')
@section('title', 'Page Manage')

@push('footer')
<link rel="stylesheet" href="{{ asset('assets/plugins/trumbowyg/ui/trumbowyg.min.css')}}?ver=1.0">
<script src="{{ asset('assets/plugins/trumbowyg/trumbowyg.min.js') }}?ver=101"></script>
@endpush

@section('content')

<div class="page-content">
    <div class="container">
        @include('vendor.notice')
        <div class="row">
            <div class="main-content col-lg-8">
                <div class="content-area card">
                    <div class="card-innr">
                        @include('layouts.messages')
                        <div class="card-head">
                            <h4 class="card-title">Pages List</h4>
                        </div>
                        <div class="gaps-1x"></div>
                        
                        <table class="table table-even-odd table-page">
                            <thead>
                                <tr>
                                    <th>Page Title</th>
                                    <th><span>Menu Name</span></th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pages as $page)
                                <tr class="page-{{ $page->id }}">
                                    <td><h5>{{ str_limit($page->title, 25) }}</h5></td>
                                    <td><p>{{ $page->menu_title }}</p></td>
                                    <td class="text-right">
                                        <ul>
                                            <li><a class="btn btn-circle btn-xs btn-icon btn-lighter" href="{{ route('admin.pages.edit', ['slug'=>$page->slug]) }}" data-slug="{{ $page->slug }}" ><em class="far fa-edit"></em></a></li>
                                        </ul>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div> {{-- .card-innr --}}

                </div> {{-- .content-area --}}
            </div>{{-- .col --}}
            <div class="aside sidebar-right col-lg-4">
                <div class="token-sales card">
                    <div class="card-innr">
                        <div class="card no-hover-shadow">
                            <div class="card-head">
                                <h5 class="card-title-md">White Paper</h5>
                            </div>
                            <div class="sap"></div>
                            <div class="pdt-3x">
                                <h6>Upload your whitepaper here.</h6>
                                <div class="upload-box">
                                    <div class="wh-upload-zone whitepaper_upload">
                                        <div class="dz-message" data-dz-message>
                                            <span class="dz-message-text">Drag and drop file</span>
                                            <span class="dz-message-or">or</span>
                                            <button type="button" class="btn btn-primary">SELECT</button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="whitepaper_file" accept="application/pdf" />
                                </div>
                                <small>Accept : pdf</small>
                                <div class="hiddenFiles"></div>
                                <div class="pt-3">
                                    @if(get_setting('site_white_paper') != '')
                                    <strong>White paper : </strong><a href="{{ route('public.white.paper') }}" target="_blank" >{{get_setting('site_white_paper')}}</a>
                                    @else
                                    <p class="text-light">No file uploaded yet!</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>{{-- .col --}}
        </div>{{-- .row --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection
