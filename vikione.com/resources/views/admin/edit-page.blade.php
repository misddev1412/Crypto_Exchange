@extends('layouts.admin')
@section('title', 'Edit Page')

@section('content')
<div class="page-content">
    <div class="container">
        <div class="card content-area">
            <div class="card-innr">
                <div class="card-head has-aside">
                    <h4 class="card-title">Edit Page "<span class="text-primary">{{ $page_data->menu_title }}</span>"</h4>
                    <div class="card-opt">
                        <ul class="btn-grp btn-grp-block guttar-20px">
                            <li>
                                <a href="{{ route('admin.pages') }}" class="btn btn-auto btn-outline btn-primary btn-sm"><i class="fas fa-arrow-left"></i><span>Back</span></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <form action="{{ route('admin.ajax.pages.update') }}" class="validate-modern" method="POST" id="update_page">
                    @csrf
                    <input type="hidden" name="page_id" value="{{ $page_data->id }}">
                    <div class="msg-box"></div>
                    <div class="input-item input-with-label">
                        <label for="menu_title" class="input-item-label">Menu Name</label>
                        <div class="input-wrap">
                            <input name="menu_title" id="menu_title" class="input-bordered" required value="{{ $page_data->menu_title }}" type="text">
                        </div>
                    </div>
                    <div class="input-item input-with-label">
                        <label for="menu_title" class="input-item-label">Slug</label>
                        <div class="input-wrap">
                            <input name="custom_slug" id="custom_slug" class="input-bordered" required value="{{ $page_data->custom_slug }}"{{ ($page_data->slug=='referral') ? ' readonly' : '' }} type="text">
                        </div>
                    </div>
                    <div class="input-item input-with-label">
                        <label for="title" class="input-item-label">Page Title</label>
                        <div class="input-wrap">
                            <input name="title" id="title" class="input-bordered" value="{{ $page_data->title }}" type="text" required="">
                        </div>
                    </div>
                    <div class="input-item  input-with-label">
                        <label for="description" class="input-item-label">Page Content</label>
                        <div class="input-wrap">
                            <textarea id="description" name="description" class="input-bordered input-textarea editor" >{{ $page_data->description }}</textarea>
                            <span class="input-note"><strong>Available Short-codes : </strong> [[token_name]], [[token_symbol]], [[site_name]], [[site_email]], [[whitepaper_download_link]], [[whitepaper_download_button]]</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-sm-3">
                            <div class="input-item input-with-label">
                                <label for="status" class="input-item-label">Visibility</label>
                                <select name="status" id="status" class="select select-bordered select-block">
                                    <option {{ $page_data->status == 'active' ? 'selected' : '' }} value="active">Show</option>
                                    <option {{ $page_data->status == 'hide' ? 'selected' : '' }} value="hide">Hide</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="gaps-1x"></div>
                    <button type="submit" class="btn btn-md btn-primary ucap">Update Page</button>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection

@push('footer')
<link rel="stylesheet" href="{{ asset('assets/plugins/trumbowyg/ui/trumbowyg.min.css')}}?ver=1.0">
<script src="{{ asset('assets/plugins/trumbowyg/trumbowyg.min.js') }}?ver=101"></script>

<script type="text/javascript">
    (function($) {
        if($('.editor').length > 0){
            $('.editor').trumbowyg({autogrow: true});
        }
        var $_form = $('form#update_page');
        if ($_form.length > 0) {
            ajax_form_submit($_form, false);
        }
    })(jQuery);
</script>
@endpush
