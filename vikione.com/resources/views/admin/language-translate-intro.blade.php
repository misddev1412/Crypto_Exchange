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
                            <h4 class="card-title">
                                @if( request()->filter )
                                {{ __(':page Translation', ['page' => $lang->name]) }}
                                @else
                                {{ __('Instruction for Translation') }}
                                @endif
                            </h4>
                            <div class="card-opt">
                                <ul class="btn-grp btn-grp-block guttar-20px">
                                    <li><a href="{{ route('admin.lang.manage') }}" class="btn btn-auto btn-sm btn-primary"><em class="fas fa-arrow-left"></em><span class="d-sm-inline-block d-none">Back</span></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="gaps-1-5x"></div>
                        <div class="card-text">
                            <p>You can easily translate <strong>Base</strong> text to <strong>{{ $lang->name }}</strong>. When you update please keep in mind few thing as below referrence.</p>
                            <ul class="list list-check">
                                <li>Once you change or update any text then must re-genarate the language file from Manage Languages page</li>
                                <li>Ensure that your 'resource/lang' folder have write permission.</li>
                                <li>On Leftside you have all type text, you navigate and update if you need.</li>
                                <li><p><strong>Important:</strong> If any where have '<span class="badge-lighter"> :TOKEN </span>', '<span class="badge-lighter"> :symbol </span>', '<span class="badge-lighter"> :currency </span>', '<span class="badge-lighter"> :amount </span>' or something like which is start with '<span class="badge-light"> : </span>' clone without space, please keep and use same as this is consider as variable & dynamically populate.</p></li>
                                <li><p>Example as below.<br><strong>Base:</strong> Choose currency and calculate :SYMBOL token price.</p>
                                    <p><strong>French:</strong> Choisissez la devise et calculez le prix du jeton :SYMBOL.</p>
                                    <p><strong>Turkish:</strong> Para birimini seçin ve :SYMBOL token fiyatını hesaplayın.</p>
                                </li>
                            </ul>
                            <h5 class="font-mid">DO or DONT</h5>
                            <p>
                                <em class="fa fa-check-circle text-success"></em> Use exactly what shown in Base text <span class="badge-lighter"> :SYMBOL </span> &nbsp;or <span class="badge-lighter"> :symbol </span><br>
                                <em class="fa fa-times-circle text-danger"></em> Do not use as <span class="badge-lighter"> SYMBOL: </span> &nbsp; (: colon after word) <br>
                                <em class="fa fa-times-circle text-danger"></em> Do not switch case <span class="badge-lighter"> :SYMBOL </span> &nbsp;to <span class="badge-lighter"> :symbol </span> &nbsp; or &nbsp; <span class="badge-lighter"> :symbol </span> to <span class="badge-lighter"> :SYMBOL </span>
                            </p>
                            <div class="gaps-1x"></div>
                            <p><strong>If you need any help or face problem please feel free to <a target="_blank" href="https://softnio.com/contact/">contact us</a>.</strong></p>
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