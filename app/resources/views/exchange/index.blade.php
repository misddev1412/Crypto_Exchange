@extends('layouts.master',['hideBreadcrumb'=>true, 'activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container-fluid">
        <div class="row no-gutters">
            <div class="col-lg-12 order-1 overflow-hidden">
                <div class="row no-gutters">
                    <div class="pb-2 pr-lg-2 col-sm-12 col-lg-9">
                        {{-- start: summery box--}}
                        @include('exchange._summery')
                        {{-- end: summery box--}}
                    </div>
                </div>
            </div>
            <div class="pb-2 pr-md-2 col-md-4 col-lg-3 h-463px order-5 order-md-5 order-lg-2 overflow-hidden">
                {{-- start: ask order book--}}
                @include('exchange._ask_order_book')
                {{-- end: ask order book--}}
            </div>
            <div class="pb-2 pr-lg-2 col-md-8 col-lg-6 h-463px order-2 order-md-3 order-lg-3 overflow-hidden">
                {{-- start: graph chart --}}
                @include('exchange._graph_chart')
                {{-- end: graph chart --}}
            </div>
            <div
                class="pb-2 pr-md-2 pr-lg-0 col-md-4 h-463px h-lg-100 col-lg-3 order-3 order-md-2 order-lg-4 overflow-hidden lf-overflow-lg-visible">
                {{-- start: markets --}}
                @include('exchange._markets')
                {{-- end: markets --}}
            </div>
            <div class="pb-2 pr-lg-2 col-md-4 col-lg-3 h-463px order-6 order-md-6 order-lg-5 overflow-hidden">
                {{-- start: bid order book --}}
                @include('exchange._bid_order_book')
                {{-- end: bid order book --}}
            </div>
            <div class="pb-2 pr-lg-2 col-md-12 col-lg-6 h-md-463px order-7 order-md-7 order-lg-6 overflow-hidden">
                {{-- start: forms --}}
                @include('exchange._forms')
                {{-- end: forms --}}
            </div>
            <div class="pb-2 pr-md-2 pr-lg-0 col-md-4 col-lg-3 h-463px order-4 order-md-4 order-lg-7 overflow-hidden">
                {{-- start: history --}}
                @include('exchange._histories')
                {{-- end: history --}}
            </div>
        </div>

        @auth
            @include('exchange._user_open_orders')
            @include('exchange._user_trade_history')
        @endauth

    </div>
@endsection

@section('script')
    <script src="{{asset('plugins/moment.js/moment.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('plugins/mScrollbar/jquery.mCustomScrollbar.concat.min.js')}}"></script>
    <script src="{{asset('plugins/bcmath/bcmath.js')}}"></script>
    <script src="{{asset('plugins/bcmath/libbcmath-min.js')}}"></script>
    <script src="{{asset('plugins/charting_library/charting_library.min.js')}}"></script>
    <script src="{{asset('plugins/charting_library/datafeed/datafeed.js')}}"></script>
    <script src="{{asset('plugins/cvalidator/cvalidator-language-en.js')}}"></script>
    <script src="{{asset('plugins/cvalidator/cvalidator.js')}}"></script>
    @include('exchange._vueInit')
    <script src="{{asset('js/chart.js')}}"></script>
    @include('exchange._market_table_script')
    @include('exchange._init_js')
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/mScrollbar/jquery.mCustomScrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
