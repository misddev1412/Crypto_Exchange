@extends('layouts.user')
@section('title', ucfirst($page->title))
@php($has_sidebar = true)

@section('content')
<div class="content-area content-area-mh card user-account-pages page-{{ $page->slug }}">
    <div class="card-innr">
        @include('layouts.messages')
        <div class="card-head">
            <h4 class="card-title card-title-lg">{{ replace_shortcode($page->title) }}</h4>
            @if($page->meta_description!=null)
            <p class="large">{{ replace_shortcode($page->meta_description) }}</p>
            @endif
        </div>
        @if(!empty($page->description))
        <div class="card-text">
            {!! replace_shortcode(auto_p($page->description)) !!}
        </div>
        @endif
	</div>
</div>
@endsection