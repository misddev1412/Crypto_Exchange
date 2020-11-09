@extends('layouts.master',['hideBreadcrumb'=> true, 'hideNotice'=> true, 'fixedSideNav'=>false, 'activeSideNav'=>active_side_nav()])
@section('content')
    @include('regular_pages._banner')
    @include('regular_pages._feature')
    @include('regular_pages._testimonial')
    @include('regular_pages._investment')
    @include('regular_pages._bitcoin')
    @include('regular_pages._team')
    @include('regular_pages._news')
@endsection
@section('script')
    @include('regular_pages._script')
@endsection
@section('style')
    @include('regular_pages._style')
@endsection
