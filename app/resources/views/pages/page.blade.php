@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('meta')
    <meta name="description" content="{{ $page->meta_description }}">
    <meta name="keywords" content="{{ array_to_string($page->meta_keywords, ',', false) }}">
@endsection
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-12">
                <div class="lf-post-content">
                    {{ view_html($page->content) }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style')
    <style>
        .privacy-nav-wrapper li {
            padding: 13px 0;
            list-style: none;
            border-bottom: 1px solid;
        }
        .privacy-nav-wrapper li:last-child {
            border-bottom: none;
        }
    </style>
@endsection
