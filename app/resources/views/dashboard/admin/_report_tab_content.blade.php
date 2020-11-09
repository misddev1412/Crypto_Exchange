<div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="pills-withdrawal" role="tabpanel" aria-labelledby="pills-withdrawal-tab">
        @include('dashboard.admin._withdrawal_report')
    </div>
    <div class="tab-pane fade" id="pills-deposit" role="tabpanel" aria-labelledby="pills-deposit-tab">
        @include('dashboard.admin._deposit_report')
    </div>
    <div class="tab-pane fade" id="pills-trade" role="tabpanel" aria-labelledby="pills-trade-tab">
        @include('dashboard.admin._trade_report')
    </div>
</div>
