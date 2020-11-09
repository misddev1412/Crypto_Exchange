@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        <!-- currency -->
        @include('dashboard.admin._featured_coins')
       <div class="row">
           <!-- recent users -->
           <div class="col-lg-8 my-3">
                @include('dashboard.admin._coin_pair_cart')
           </div>
           <!-- reports -->
           <div class="col-lg-4 my-3">
               <!-- users -->
               @include('dashboard.admin._user_reports')

               <!-- ticket -->
               @include('dashboard.admin._ticket_reports')
           </div>
       </div>
        <!-- count reports -->
        @include('dashboard.admin._count_report')
        <!-- users, deposit, withdrawal and trade reports -->
        <div class="row my-3">
           <!-- Recent Users -->
           <div class="col-lg-6 col-sm-12 my-3">
                @include('dashboard.admin._recent_register_users')
           </div>
            <!-- recent deposit, withdrawal and trade  reports -->
            <div class="col-lg-6 my-3 col-sm-12">
                @include('dashboard.admin._report_tab_navs')
                @include('dashboard.admin._report_tab_content')
            </div>
       </div>
    </div>
@endsection

@section('style')
    @include('layouts.includes.list-css')
    <style>
        .report-box.border > .content > .amount {
            font-size: 44px;
            font-weight: 600;
            line-height: 1;
            margin: 0;
        }
        .card.count-box {
            min-height: 110px;
        }
    </style>
@endsection

@section('script')
    @include('dashboard.admin._script')
@endsection

