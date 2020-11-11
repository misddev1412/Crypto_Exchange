@extends('public.base')
@section('title', ucfirst($page->title))
@section('content')

<div class="page-header page-header-kyc">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7 text-center">
             <h2 class="page-title">{{ replace_shortcode($page->title) }}</h2>
             @if($page->meta_description!==null)
	         <p class="large">{{ replace_shortcode($page->meta_description) }}</p>
	         @endif
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
        <div class="card content-area">
            <div class="card-innr">
                <div class="card-text">
                	{!! replace_shortcode($page->description) !!}
                </div>
            </div>
        </div>{{-- .card --}}
        <div class="gaps-1x"></div>
        <div class="gaps-3x d-none d-sm-block"></div>
    </div>
</div>

@endsection