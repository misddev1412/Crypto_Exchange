@extends('layouts.admin')
@section('title', ' Translate Language')
@push('header')
<style type="text/css">
    .sidebar-nav li.active>a {font-weight: bold;}
</style>
@endpush
@section('content')
<div class="page-content">
    <div class="container">
        @include('vendor.notice')
        <div class="row">
            <div class="col-lg-3 aside sidebar-left">
                <div class="card card-navs">
                    <div class="card-innr">
                        <div class="card-head d-none d-lg-block">
                            <h6 class="card-title card-title-md">{{ __(':lang Translation', ['lang' => $lang->name]) }}</h6>
                        </div>
                        <ul class="sidebar-nav">
                            <li><a href="{{ url()->current() }}"><em class="ikon ikon-dashboard"></em> Instruction</a></li>
                            @foreach($tags as $name => $term )
                            <li{!! (url()->full() == qs_url(['category' => $term, 'filter' => true], url()->current())) ? ' class="active"' : '' !!}><a href="{{ qs_url(['category' => $term, 'filter' => true], url()->current()) }}"><em class="ikon ikon-transactions"></em> {{ $name }}</a></li>
                            @endforeach
                            <li{!! (url()->full() == qs_url(['filter' => true], url()->current())) ? ' class="active"' : '' !!}><a href="{{ qs_url(['filter' => true], url()->current()) }}"><em class="ikon ikon-docs"></em> All Translation</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="main-content col-lg-9">
                <div class="content-area card">
                    <div class="card-innr">
                        <div class="card-head has-aside">
                            <h4 class="card-title">{{ __(':page Translation', ['page' => $lang->name]) }} <small class="fs-12 ucap text-light"> (Total: {{ count($translates) }} Found)</small></h4>
                            <div class="card-opt">
                                <ul class="btn-grp btn-grp-block guttar-20px">
                                    <li><a href="{{ route('admin.lang.manage') }}" class="btn btn-auto btn-sm btn-primary"><em class="fas fa-arrow-left"></em><span class="d-sm-inline-block d-none">Back</span></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="gaps-2x"></div>
                        <div class="card-text">
                            <form class="validate-modern lang-translation" action="{{ route('admin.ajax.lang.translate.action') }}" method="POST">
                                @foreach ($translates as $translate)
                                <div class="translate-item mgb-3x">
                                    <div class="row align-items-baseline mb-2">
                                        <div class="col-sm-3 col-md-2">
                                            <label class="input-item-label">{{ __('Default Text') }}</label>
                                        </div>
                                        <div class="col-sm-9 col-md-10">
                                            <div class="input-wrap">
                                                <textarea class="input-bordered input-textarea-min" rows="1" name="base[{{ $translate['id'] }}]" disabled>{{ $translate['base'] }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-baseline">
                                        <div class="col-sm-3 col-md-2">
                                            <label class="input-item-label">{{ __(':lang Text', ['lang' => $lang->name]) }}</label>
                                        </div>
                                        <div class="col-sm-9 col-md-10">
                                            <div class="input-wrap">
                                                <textarea class="input-bordered input-textarea-min" rows="1" name="{{ $lang->code }}[{{ $translate['id'] }}]" required>{{ $translate['text'] }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <div class="fake-class text-right">
                                    @csrf
                                    <input type="hidden" name="actions" value="translation">
                                    <input type="hidden" name="lang" value="{{ $lang->code }}">
                                    <button type="submit"  class="btn btn-primary" id="creatLang">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>{{-- .card-innr --}}
                </div>{{-- .card --}}
            </div>{{-- .col --}}
        </div>{{-- .row --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection

@push('footer')
<script type="text/javascript">
    (function($){
        var $translation = $(".lang-translation");
        if ($translation.length > 0) { ajax_form_submit($translation, false); }
    })(jQuery);
</script>
@endpush