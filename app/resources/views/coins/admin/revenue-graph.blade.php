@extends('layouts.master',['activeSideNav' => active_side_nav()])

@section('title', $title)

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card lf-toggle-border-color">
                    <div class="row no-gutters bg-info align-items-center">
                        <div class="col-md-4 text-center text-white p-4">
                            <img src="{{ get_cart_icon('deposit.svg') }}"
                                 alt="deposit">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body py-2 px-3 lf-toggle-bg-card lf-toggle-text-color-75">
                                <h5 class="text-lg-left font-weight-bold mb-1">{{ __('Total Deposit') }}</h5>
                                <p class="mb-1 lf-toggle-text-color-50 mb-2">@{{ totalDeposit }}</p>

                                <h5 class="text-lg-left font-weight-bold mb-1">{{ __('Total Revenue') }}</h5>
                                <p class="mb-1 lf-toggle-text-color-50 mb-0">@{{ totalDepositRevenue }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card lf-toggle-border-color">
                    <div class="row no-gutters bg-danger align-items-center">
                        <div class="col-md-4 text-center text-white p-4">
                            <img src="{{ get_cart_icon('withdrawal.svg') }}"
                                 alt="withdrawal">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body py-2 px-3 lf-toggle-bg-card lf-toggle-text-color-75">
                                <h5 class="text-lg-left font-weight-bold mb-1">{{ __('Total Withdrawal') }}</h5>
                                <p class="mb-1 lf-toggle-text-color-50 mb-2">@{{ totalWithdrawal }}</p>

                                <h5 class="text-lg-left font-weight-bold mb-1">{{ __('Total Revenue') }}</h5>
                                <p class="mb-1 lf-toggle-text-color-50 mb-0">@{{ totalWithdrawalRevenue }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card lf-toggle-border-color">
                    <div class="row no-gutters bg-success align-items-center">
                        <div class="col-md-4 text-center text-white p-4">
                            <img src="{{ get_cart_icon('trading.svg') }}"
                                 alt="trading">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body py-2 px-3 lf-toggle-bg-card lf-toggle-text-color-75">
                                <h5 class="text-lg-left font-weight-bold mb-1">{{ __('Total Trading') }}</h5>
                                <p class="mb-1 lf-toggle-text-color-50 mb-2">@{{ totalTrade }}</p>

                                <h5 class="text-lg-left font-weight-bold mb-1">{{ __('Total Revenue') }}</h5>
                                <p class="mb-1 lf-toggle-text-color-50 mb-0">@{{ totalGrossRevenue }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="position-relative">
                    <div class="cart-loader border lf-toggle-border-color lf-toggle-bg-card" v-bind:class="{hide : hideDepositGraphLoader}">
                        <div class="lds-cart m-auto">
                            <div class="lf-toggle-bg-reverse"></div>
                            <div class="lf-toggle-bg-reverse"></div>
                            <div class="lf-toggle-bg-reverse"></div>
                        </div>
                    </div>
                    @component('components.card', ['class' => 'lf-toggle-border-color lf-toggle-bg-card', 'headerClass' => 'lf-toggle-border-color', 'footerClass' => 'lf-toggle-border-color'])
                        @slot('header')
                            <h5 class="card-title text-center">{{ __("Deposit Revenue: :date", ['date' => date('F Y')]) }}</h5>
                        @endslot
                        <canvas id="deposit-chart"></canvas>
                        @slot('footer')
                            <div class="row text-center">
                                <div class="col-md-6 border-right lf-toggle-border-color">
                                    <h5>@{{ totalDeposit }}</h5>
                                    <span>{{ __("Monthly Total Deposit") }}</span>
                                </div>
                                <div class="col-md-6">
                                    <h5>@{{ totalDepositRevenue }}</h5>
                                    <span>{{ __("Monthly Total Revenue") }}</span>
                                </div>
                            </div>
                        @endslot
                    @endcomponent
                </div>
            </div>
            <div class="col-md-6">
                <div class="position-relative">
                    <div class="cart-loader border lf-toggle-border-color lf-toggle-bg-card" v-bind:class="{hide : hideWithdrawalGraphLoader}">
                        <div class="lds-cart m-auto">
                            <div class="lf-toggle-bg-reverse"></div>
                            <div class="lf-toggle-bg-reverse"></div>
                            <div class="lf-toggle-bg-reverse"></div>
                        </div>
                    </div>
                    @component('components.card', ['class' => 'lf-toggle-border-color lf-toggle-bg-card', 'headerClass' => 'lf-toggle-border-color', 'footerClass' => 'lf-toggle-border-color'])
                        @slot('header')
                            <h5 class="card-title text-center">{{ __("Withdrawal Revenue: :date", ['date' => date('F Y')]) }}</h5>
                        @endslot
                        <canvas id="withdrawal-chart"></canvas>
                        @slot('footer')
                            <div class="row text-center">
                                <div class="col-md-6 border-right lf-toggle-border-color">
                                    <h5>@{{ totalWithdrawal }}</h5>
                                    <span>{{ __("Monthly Total Withdrawal") }}</span>
                                </div>
                                <div class="col-md-6">
                                    <h5>@{{ totalWithdrawalRevenue }}</h5>
                                    <span>{{ __("Monthly Total Revenue") }}</span>
                                </div>
                            </div>
                        @endslot
                    @endcomponent
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="position-relative">
                    <div class="cart-loader border lf-toggle-border-color lf-toggle-bg-card" v-bind:class="{hide : hideTradingGraphLoader}">
                        <div class="lds-cart m-auto">
                            <div class="lf-toggle-bg-reverse"></div>
                            <div class="lf-toggle-bg-reverse"></div>
                            <div class="lf-toggle-bg-reverse"></div>
                        </div>
                    </div>
                    @component('components.card', ['class' => 'lf-toggle-border-color lf-toggle-bg-card', 'headerClass' => 'lf-toggle-border-color', 'footerClass' => 'lf-toggle-border-color'])
                        @slot('header')
                            <h5 class="card-title text-center">{{ __("Trading Revenue: :date", ['date' => date('F Y')]) }}</h5>
                        @endslot
                        <canvas id="trade-chart"></canvas>
                        @slot('footer')
                            <div class="row text-center">
                                <div class="col-md-3 border-right lf-toggle-border-color">
                                    <h5>@{{ totalTrade }}</h5>
                                    <span>{{ __("Monthly Total Trading") }}</span>
                                </div>
                                <div class="col-md-3 border-right lf-toggle-border-color">
                                    <h5>@{{ totalGrossRevenue }}</h5>
                                    <span>{{ __("Monthly Total Gross Revenue") }}</span>
                                </div>
                                <div class="col-md-3 border-right lf-toggle-border-color">
                                    <h5>@{{ totalNetRevenue }}</h5>
                                    <span>{{ __("Monthly Total Net Revenue") }}</span>
                                </div>
                                <div class="col-md-3">
                                    <h5>@{{ totalReferralExpense }}</h5>
                                    <span>{{ __("Monthly Total Referral Expense") }}</span>
                                </div>
                            </div>
                        @endslot
                    @endcomponent
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('plugins/moment.js/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/chartjs/Chart.min.js') }}"></script>
    <script>
        "use strict";

        const app = new Vue({
            el: '#app',
            data: {
                totalDeposit : 0,
                totalDepositRevenue : 0,
                totalWithdrawal : 0,
                totalWithdrawalRevenue : 0,
                totalTrade : 0,
                totalGrossRevenue : 0,
                totalNetRevenue : 0,
                totalReferralExpense : 0,
                hideDepositGraphLoader : false,
                hideWithdrawalGraphLoader : false,
                hideTradingGraphLoader : false,
            },
            methods: {
                depositCart: function (depositRevenueGraphData) {
                    new Chart($('#deposit-chart'), {
                        type: 'line',
                        data: {
                            labels: depositRevenueGraphData.days,
                            datasets: [{
                                data: depositRevenueGraphData.revenues,
                                backgroundColor: 'rgba(14,149,203,0.22)',
                                pointBackgroundColor: '#00B9F2',
                                borderWidth: 1,
                            }]
                        },
                        options: {
                            responsive: true,
                            legend: {
                                display: false
                            },
                            layout: {
                                padding: {
                                    left: 5,
                                    right: 5,
                                    top: 5,
                                }
                            },
                            tooltips: {
                                displayColors: false,
                                callbacks: {
                                    title: function (tooltipItem, data) {
                                        let day = tooltipItem[0].xLabel > 9 ? tooltipItem[0].xLabel : "0"+ tooltipItem[0].xLabel;
                                        return "Date: " + moment().format("YYYY-MM") + "-" + day;
                                    },
                                    label: function (tooltipItem, data) {
                                        return "Amount: " + Number(tooltipItem.yLabel).toFixed(8) + " {{ $coin->symbol }}";
                                    },
                                    labelColor: function (tooltipItem, chart) {
                                        return false;
                                    },
                                }
                            },
                            scales: {
                                xAxes: [{
                                    gridLines: {
                                        display: false
                                    },
                                    ticks: {
                                        display: false
                                    }
                                }],
                                yAxes: [{
                                    gridLines: {
                                        display: false
                                    },
                                    ticks: {
                                        display: false,
                                        beginAtZero: true,
                                    }
                                }]
                            }
                        }
                    });
                },
                withdrawalChart: function (withdrawalRevenueGraphData) {
                    new Chart($('#withdrawal-chart'), {
                        type: 'line',
                        data: {
                            labels: withdrawalRevenueGraphData.days,
                            datasets: [{
                                data: withdrawalRevenueGraphData.revenues,
                                backgroundColor: 'rgba(239,57,78,0.22)',
                                pointBackgroundColor: '#f20030',
                                borderWidth: 1,
                            }]
                        },
                        options: {
                            responsive: true,
                            legend: {
                                display: false
                            },
                            layout: {
                                padding: {
                                    left: 5,
                                    right: 5,
                                    top: 5,
                                }
                            },
                            tooltips: {
                                displayColors: false,
                                callbacks: {
                                    title: function (tooltipItem, data) {
                                        let day = tooltipItem[0].xLabel > 9 ? tooltipItem[0].xLabel : "0"+ tooltipItem[0].xLabel;
                                        return "Date: " + moment().format("YYYY-MM") + "-" + day;
                                    },
                                    label: function (tooltipItem, data) {
                                        return "Amount: " + Number(tooltipItem.yLabel).toFixed(8) + " {{ $coin->symbol }}";
                                    },
                                    labelColor: function (tooltipItem, chart) {
                                        return false;
                                    },
                                }
                            },
                            scales: {
                                xAxes: [{
                                    gridLines: {
                                        display: false
                                    },
                                    ticks: {
                                        display: false
                                    }
                                }],
                                yAxes: [{
                                    gridLines: {
                                        display: false
                                    },
                                    ticks: {
                                        display: false,
                                        beginAtZero: true,
                                    }
                                }]
                            }
                        }
                    });
                },
                tradeChart: function (tradeRevenueGraphData) {
                    new Chart($('#trade-chart'), {
                        type: 'line',
                        data: {
                            labels: tradeRevenueGraphData.days,
                            datasets: [
                                {
                                    label: "Gross Revenue",
                                    data: tradeRevenueGraphData.gross_revenues,
                                    fill: false,
                                    borderColor: '#139dc7',
                                    backgroundColor: '#139dc7',
                                    borderWidth: 1,
                                },
                                {
                                    label: "Net Revenue",
                                    borderDash: [3, 3],
                                    data: tradeRevenueGraphData.net_revenues,
                                    fill: false,
                                    borderColor: '#0dbf25',
                                    backgroundColor: '#0dbf25',
                                    borderWidth: 1,
                                },
                                {
                                    label: "Referral Expense",
                                    data: tradeRevenueGraphData.referral_expenses,
                                    fill: false,
                                    borderColor: '#d7350d',
                                    backgroundColor: '#d7350d',
                                    borderWidth: 1,
                                }]
                        },
                        options: {
                            responsive: true,
                            layout: {
                                padding: {
                                    left: 5,
                                    right: 5,
                                    top: 5,
                                }
                            },
                            tooltips: {
                                displayColors: false,
                                callbacks: {
                                    title: function (tooltipItem, data) {
                                        let day = tooltipItem[0].xLabel > 9 ? tooltipItem[0].xLabel : "0"+ tooltipItem[0].xLabel;
                                        return "Date: " + moment().format("YYYY-MM") + "-" + day;
                                    },
                                    label: function (tooltipItem, data) {
                                        let prefix = "Referral Expense: "
                                        if (tooltipItem.datasetIndex === 0) {
                                            prefix = "Gross Revenue: "
                                        }else if (tooltipItem.datasetIndex === 1){
                                            prefix = "Net Revenue: "
                                        }
                                        return prefix + Number(tooltipItem.yLabel).toFixed(8) + " {{ $coin->symbol }}";
                                    },
                                    labelColor: function (tooltipItem, chart) {
                                        return false;
                                    },
                                }
                            },
                            scales: {
                                display: true,
                                xAxes: [{
                                    gridLines: {
                                        display: true,
                                        drawBorder: true,
                                        drawOnChartArea: false,
                                    },
                                    ticks: {
                                        display: true,
                                    }
                                }],
                                yAxes: [{
                                    gridLines: {
                                        display: true,
                                        drawBorder: true,
                                        drawOnChartArea: false,
                                    },
                                    ticks: {
                                        display: true,
                                        beginAtZero: true,
                                        callback: function (value, index, values) {
                                            return Number(value).toFixed(8);
                                        }
                                    }
                                }]
                            }
                        }
                    });
                }
            },
            mounted: function () {
                const instant = this;
                axios.get('{{ route('coins.revenue-graph.deposit', $coin) }}')
                    .then(function (response) {
                        var responseData = response.data.depositRevenueGraph;
                        instant.depositCart(responseData);
                        instant.totalDeposit = responseData.total_deposit;
                        instant.totalDepositRevenue = responseData.total_revenue;
                    })
                    .then(function () {
                        instant.hideDepositGraphLoader = true;
                    });

                axios.get('{{ route('coins.revenue-graph.withdrawal', $coin) }}')
                    .then(function (response) {
                        var responseData = response.data.withdrawalRevenueGraph;
                        instant.withdrawalChart(responseData);
                        instant.totalWithdrawal = responseData.total_withdrawal;
                        instant.totalWithdrawalRevenue = responseData.total_revenue;
                    })
                    .then(function () {
                        instant.hideWithdrawalGraphLoader = true;
                    });

                axios.get('{{ route('coins.revenue-graph.trade-revenue', $coin) }}')
                    .then(function (response) {
                        var responseData = response.data.tradeRevenueGraph;
                        instant.tradeChart(responseData);
                        instant.totalTrade = responseData.total_trade
                        instant.totalGrossRevenue = responseData.total_gross_revenue
                        instant.totalNetRevenue = responseData.total_net_revenue
                        instant.totalReferralExpense = responseData.total_referral_expense
                    })
                    .then(function () {
                        instant.hideTradingGraphLoader = true;
                    });
            }
        })
    </script>
@endsection
