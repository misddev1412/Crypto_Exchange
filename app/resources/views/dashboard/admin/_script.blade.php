<script src="{{ asset('plugins/moment.js/moment.min.js') }}"></script>
<script src="{{ asset('plugins/chartjs/Chart.min.js') }}"></script>
<script>
    "use strict";

    const app = new Vue({
        el: '#app',
        data: {
            hideFeaturedCoinLoader: false,
            hideCoinPairCartLoader: false,
            hideCountReportLoader: false,
            hideOthersReportLoader: false,
            hideRecentRegisterUserLoader: false,
            hideWithdrawalReportLoader: false,
            hideDepositReportLoader: false,
            hideTradeReportLoader: false,
            hideFeaturedCoin: false,
            hideUserReportLoader: false,
            hideTicketReportLoader: false,
            totalUsers: 0,
            totalDeposits: 0,
            totalWithdrawals: 0,
            totalTrade: 0,
            totalRevenue: 0,
            totalCoinPairTrade: 0,
            coinPairName: '',
            recentUserListView: '',
            featuredCoins: [],
            totalUser: 0,
            totalActiveUser: 0,
            totalVerifiedUser: 0,
            totalSuspendedUser: 0,
            totalTicket: 0,
            totalOpenTicket: 0,
            totalClosedTicket: 0,
            totalResolvedTicket: 0,
            totalCoin: 0,
            totalActiveCoin: 0,
            totalCoinPair: 0,
            totalActiveCoinPair: 0,
            totalPost: 0,
            totalComment: 0,
            totalPendingReviewKyc: 0,
            totalPendingDeposit: 0,
            totalPendingWithdrawal: 0,
            recentWithdrawalView: '',
            recentDepositView: '',
            recentTradeView: '',
        },
        methods: {
            coinPairCart: function (coinPairTradeData) {
                new Chart($('#tradeCart'), {
                    type: 'line',
                    data: {
                        labels: coinPairTradeData.days,
                        datasets: [
                            {
                                data: coinPairTradeData.revenues,
                                backgroundColor: 'rgba(14,149,203,0.22)',
                                pointBackgroundColor: '#00B9F2',
                                borderWidth: 1,
                            }
                        ]
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
                                    return "Day: " + tooltipItem[0].xLabel;
                                },
                                label: function (tooltipItem, data) {
                                    let prefix = "Total Trade: "
                                    if (tooltipItem.datasetIndex === 1) {
                                        prefix = "Net Revenue: "
                                    }
                                    return prefix + Intl.NumberFormat().format((tooltipItem.yLabel/1000)) + 'K';
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
                                    display: false
                                },
                                ticks: {
                                    display: true,
                                }
                            }],
                            yAxes: [{
                                gridLines: {
                                    display: false
                                },
                                ticks: {
                                    display: true,
                                    // beginAtZero: true,
                                    callback: function (value, index, values) {
                                        return Intl.NumberFormat().format((value/1000)) + 'K';
                                    }
                                }
                            }]
                        }
                    }
                });
            },
            pieChart: function (ctx, data, labels) {
                var pieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            backgroundColor: ['#00deff', '#82C700', '#6c757d', '#F1045C'],
                            borderColor: ['#00deff', '#82C700', '#6c757d', '#F1045C'],
                            data: data
                        }],
                    },
                    options: {
                        cutoutPercentage: 50,
                        legend: {
                            display: false
                        },
                    }
                });
            }
        },
        mounted: function () {
            const instant = this;
            // load featured coins
            axios.get('{{ route('admin.dashboard.get-featured-coins') }}')
                .then(function (response) {
                    if (response.data.status === "{{ RESPONSE_TYPE_ERROR }}") {
                        instant.featuredCoins = [];
                        instant.hideFeaturedCoin = true;
                    } else {
                        instant.featuredCoins = response.data.data.dashboardCoins;
                        instant.hideFeaturedCoin = false;
                    }
                })
                .catch(function (error) {
                    flashBox("{{RESPONSE_TYPE_ERROR}}", "{{ __('Something went wrong.') }}");
                })
                .then(function () {
                    instant.hideFeaturedCoinLoader = true;
                });
            // load recent register users
            axios.get('{{ route('admin.dashboard.get-recent-register-users') }}')
                .then(function (response) {
                    instant.recentUserListView = response.data.view;
                })
                .catch(function (error) {
                    flashBox("{{RESPONSE_TYPE_ERROR}}", "{{ __('Something went wrong.') }}");
                })
                .then(function () {
                    instant.hideRecentRegisterUserLoader = true;
                });

            // load coin pair data
            axios.get('{{ route('admin.dashboard.get-coin-pair-trade') }}')
                .then(function (response) {
                    var coinPairTradeData = response.data.coinPairTrade;
                    instant.coinPairName = coinPairTradeData.coinPairName;
                    instant.totalRevenue = coinPairTradeData.revenue;
                    instant.totalCoinPairTrade = coinPairTradeData.total;
                    instant.coinPairCart(coinPairTradeData);
                })
                .catch(function (error) {
                    flashBox("{{RESPONSE_TYPE_ERROR}}", "{{ __('Something went wrong.') }}");
                })
                .then(function () {
                    instant.hideCoinPairCartLoader = true;
                });
            axios.get('{{ route('admin.dashboard.get-recent-withdrawals') }}')
                .then(function (response) {
                    instant.recentWithdrawalView = response.data.view;
                })
                .catch(function (error) {
                    flashBox("{{RESPONSE_TYPE_ERROR}}", "{{ __('Something went wrong.') }}");
                })
                .then(function () {
                    instant.hideWithdrawalReportLoader = true;
                });
            axios.get('{{ route('admin.dashboard.get-recent-deposits') }}')
                .then(function (response) {
                    instant.recentDepositView = response.data.view;
                })
                .catch(function (error) {
                    flashBox("{{RESPONSE_TYPE_ERROR}}", "{{ __('Something went wrong.') }}");
                })
                .then(function () {
                    instant.hideDepositReportLoader = true;
                });
            axios.get('{{ route('admin.dashboard.get-recent-trades') }}')
                .then(function (response) {
                    instant.recentTradeView = response.data.view;
                })
                .catch(function (error) {
                    flashBox("{{RESPONSE_TYPE_ERROR}}", "{{ __('Something went wrong.') }}");
                })
                .then(function () {
                    instant.hideTradeReportLoader = true;
                });
            axios.get('{{ route('admin.dashboard.get-user-reports') }}')
                .then(function (response) {
                    var userReports = response.data.userReports;
                    instant.totalUser = userReports.totalUsers;
                    instant.totalActiveUser = userReports.totalActiveUsers;
                    instant.totalVerifiedUser = userReports.totalVerifiedUsers;
                    instant.totalSuspendedUser = userReports.totalSuspendedUsers;
                    instant.pieChart(
                        $('#userChart'),
                        [
                            instant.totalUser,
                            instant.totalActiveUser,
                            instant.totalVerifiedUser,
                            instant.totalSuspendedUser
                        ],
                        [
                            'total', 'active', 'verified', 'suspended'
                        ]
                    )
                })
                .catch(function (error) {
                    flashBox("{{RESPONSE_TYPE_ERROR}}", "{{ __('Something went wrong.') }}");
                })
                .then(function () {
                    instant.hideUserReportLoader = true;
                });

            axios.get('{{ route('admin.dashboard.get-ticket-reports') }}')
                .then(function (response) {
                    var ticketReports = response.data.ticketReports;
                    instant.totalTicket = ticketReports.totalTicket;
                    instant.totalResolvedTicket = ticketReports.totalResolvedTicket;
                    instant.totalOpenTicket = ticketReports.totalOpenTicket;
                    instant.totalClosedTicket = ticketReports.totalClosedTicket;
                    instant.pieChart(
                        $('#ticketChart'),
                        [
                            instant.totalTicket,
                            instant.totalOpenTicket,
                            instant.totalClosedTicket,
                            instant.totalResolvedTicket
                        ],
                        [
                            'total', 'open', 'closed', 'resolved'
                        ]
                    )
                })
                .catch(function (error) {
                    flashBox("{{RESPONSE_TYPE_ERROR}}", "{{ __('Something went wrong.') }}");
                })
                .then(function () {
                    instant.hideTicketReportLoader = true;
                });

            axios.get('{{ route('admin.dashboard.get-other-reports') }}')
                .then(function (response) {
                    var reports = response.data.reports;
                    instant.totalCoin = reports.totalCoin,
                        instant.totalActiveCoin = reports.totalActiveCoin;
                    instant.totalCoinPair = reports.totalCoinPair;
                    instant.totalActiveCoinPair = reports.totalActiveCoinPair;
                    instant.totalPost = reports.totalPost;
                    instant.totalComment = reports.totalComment;
                    instant.totalPendingReviewKyc = reports.totalPendingReviewKyc;
                    instant.totalPendingDeposit = reports.totalPendingDeposit;
                    instant.totalPendingWithdrawal = reports.totalPendingWithdrawal;
                })
                .catch(function (error) {
                    flashBox("{{RESPONSE_TYPE_ERROR}}", "{{ __('Something went wrong.') }}");
                })
                .then(function () {
                    instant.hideOthersReportLoader = true;
                });
        }
    })
</script>
