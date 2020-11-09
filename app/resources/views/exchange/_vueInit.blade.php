<script>
    "use strict";

    const vueInstance = new Vue({
        el: '#app',
        data: {
            pairDetail: {
                tradePair: "{{ $coinPair->name }}",
                name: "{{ $coinPair->trade_pair }}",
                tradeCoin: "{{ $coinPair->trade_coin }}",
                tradeCoinName: "",
                tradeCoinIcon: "",
                baseCoin: "{{ $coinPair->base_coin }}",
                baseCoinIcon: "",
                lastPrice: "",
                change24hr: "",
                changeColorText: '',
                high24hr: "",
                low24hr: "",
                baseCoinVolume: "",
                tradeCoinVolume: "",
                loading: true
            },
            askOrderDetail: {
                totalBaseCoinAskedOrder: "",
                totalTradeCoinAskedOrder: ""
            },
            bidOrderDetail: {
                totalBaseCoinBidOrder: "",
                totalTradeCoinBidOrder: ""
            },
            chartOptions: {
                separator: '-',
                initialData: [['1111', 0, 0, 0, 0]],
                interval: 900,
                start: "3d",
                rootPath: null,
                chartColor: {},
                currentTimeFrameIndex: 5,
                minimumTimeFrameIndex: 5,
                doAjax: true,
                fromDate: 2592000
            },
            marketFormTab: {tab: 'limit-form-tab'},
            currentLowestPrice: null,
            currentHighestPrice: null,
            limitBuyFormData: {
                price: null,
                amount: null,
                total: null,
                placingOrder: false
            },
            limitSellFormData: {
                price: null,
                amount: null,
                total: null,
                placingOrder: false
            },
            stopLimitBuyFormData: {
                stop: null,
                price: null,
                amount: null,
                total: null,
                placingOrder: false
            },
            stopLimitSellFormData: {
                stop: null,
                price: null,
                amount: null,
                total: null,
                placingOrder: false
            },
            marketBuyFormData: {
                total: null,
                placingOrder: false
            },
            marketSellFormData: {
                amount: null,
                placingOrder: false
            },
            askOrderTableInstance: null,
            bidOrderTableInstance: null,
            marketTradeHistoryTableInstance: null,
            marketTableInstance: null,
            settingTolerance: "{{ settings('trading_price_tolerance') }}",
            @auth
            user: {},
            orderCancelUrl: "{{ route('user.order.destroy', ['order' => '##'] ) }}",
            userOpenOrderTableInstance: null,
            userTraderHistoryTableInstance: null,
            @endauth
        },
        computed: {
            orderBroadcastChannel: function () {
                return "{{ config('broadcasting.prefix') }}" + 'order.' + this.pairDetail.tradePair;
            }
        },
        methods: {
            onStopPriceChangeHandler(event, type) {
                let newPriceRules = {
                    limit: "required|numeric|decimalScale:11,8|between:" + this.getPriceTolerance(event.target.value).join(','),
                };
                eval(type).setRules(newPriceRules);
            },
            setLimitBuyPrice(price) {
                this.limitBuyFormData.price = price;
            },
            setLimitBuyAmount(amount) {
                this.limitBuyFormData.amount = amount;
            },
            setLimitBuyTotal(total) {
                this.limitBuyFormData.total = total;
            },
            setLimitSellPrice(price) {
                this.limitSellFormData.price = price;
            },
            setLimitSellAmount(amount) {
                this.limitSellFormData.amount = amount;
            },
            setLimitSellTotal(total) {
                this.limitSellFormData.total = total;
            },
            resetLimitBuyPrice() {
                this.limitBuyFormData.price = this.currentLowestPrice;
                this.limitBuyFormData.amount = null;
                this.limitBuyFormData.total = null;
            },
            resetLimitSellPrice() {
                this.limitSellFormData.price = this.currentHighestPrice;
                this.limitSellFormData.amount = null;
                this.limitSellFormData.total = null;
            },
            resetStopLimitSellPrice() {
                this.stopLimitSellFormData.price = this.currentHighestPrice;
                this.stopLimitSellFormData.stop = this.currentHighestPrice;
                this.stopLimitSellFormData.amount = null;
                this.stopLimitSellFormData.total = null;
            },
            resetMarketBuyForm() {
                this.marketBuyFormData.total = null;
            },
            resetMarketSellForm() {
                this.marketSellFormData.amount = null;
            },
            resetStopLimitBuyPrice() {
                this.stopLimitBuyFormData.price = this.currentLowestPrice;
                this.stopLimitBuyFormData.stop = this.currentLowestPrice;
                this.stopLimitBuyFormData.amount = null;
                this.stopLimitBuyFormData.total = null;
            },
            setStopLimitValue(price) {
                this.stopLimitBuyFormData.stop = price;
                this.stopLimitBuyFormData.price = price;
                this.stopLimitBuyFormData.total = null;

                this.stopLimitSellFormData.stop = price;
                this.stopLimitSellFormData.price = price;
                this.stopLimitSellFormData.total = null;

                this.onChangeProduceTotalHandler('stopLimitBuyFormData');
                this.onChangeProduceTotalHandler('stopLimitSellFormData');
            },
            onChangeProduceTotalHandler(targetProperty) {
                const targetedProperty = this[targetProperty];
                targetedProperty.total = targetedProperty.price >= 0.00000001 && targetedProperty.amount >= 0.00000001 ?
                    bcmul(targetedProperty.amount, targetedProperty.price, 8) : null;
            },
            onChangeProduceAmountHandler(targetProperty) {
                const targetedProperty = this[targetProperty];
                targetedProperty.amount = targetedProperty.total >= 0.00000001 && targetedProperty.price >= 0.00000001 ?
                    bcdiv(targetedProperty.total, targetedProperty.price, 8) : null;
            },
            onClickProduceAmountByPercentHandler(targetProperty, percent, type) {
                const targetedProperty = this[targetProperty];
                let calculatedNumber = null;

                if (type === 'buy') {
                    calculatedNumber = this.user.baseCoinBalance && this.user.baseCoinBalance >= 0.00000001 ?
                        this._calculatePercentage(this.user.baseCoinBalance, percent) : null;
                    targetedProperty.total = calculatedNumber;
                    this.onChangeProduceAmountHandler(targetProperty);
                } else {
                    calculatedNumber = this.user.tradeCoinBalance && this.user.tradeCoinBalance >= 0.00000001 ?
                        this._calculatePercentage(this.user.tradeCoinBalance, percent) : null;
                    targetedProperty.amount = calculatedNumber;
                    this.onChangeProduceTotalHandler(targetProperty);
                }
            },
            _calculatePercentage(volume, percent) {
                return parseFloat(percent) === 100 ? volume : bcdiv(bcmul(volume, percent, 8), 100, 8);
            },
            updateCoinPair24hrSummary(data) {
                this.pairDetail.loading = true;

                this.pairDetail.name = data.trade_pair_name;
                this.pairDetail.tradePair = data.trade_pair;
                this.pairDetail.tradeCoinName = data.trade_coin_name;
                this.pairDetail.tradeCoin = data.trade_coin;
                this.pairDetail.tradeCoinIcon = data.trade_coin_icon;
                this.pairDetail.baseCoin = data.base_coin;
                this.pairDetail.baseCoinIcon = data.base_coin_icon;
                this.pairDetail.lastPrice = bcmul(data.latest_price, 1, 8);
                this.pairDetail.change24hr = bcmul(data.change, 1, 2) + '%';
                this.pairDetail.high24hr = bcmul(data.high_price, 1, 8);
                this.pairDetail.low24hr = bcmul(data.low_price, 1, 8);
                this.pairDetail.baseCoinVolume = data.base_coin_volume;
                this.pairDetail.tradeCoinVolume = data.trade_coin_volume;

                if (parseFloat(data.change) > 0) {
                    this.pairDetail.changeColorText = 'text-green';
                } else if (parseFloat(data.change) < 0) {
                    this.pairDetail.changeColorText = 'text-pink';
                } else {
                    this.pairDetail.changeColorText = 'lf-toggle-text-color-50';
                }

                this.pairDetail.loading = false;

                let newPriceRules = {
                    price: "required|numeric|decimalScale:11,8|between:" + this.getPriceTolerance(this.pairDetail.lastPrice).join(','),
                };
                limitBuyForm.setRules(newPriceRules);
                limitSellForm.setRules(newPriceRules);
            },
            initAskOrderTable() {
                const currentInstance = this;
                currentInstance.askOrderTableInstance = $(document).find('#ask-order-table').DataTable({
                    paging: false,
                    order: [[0, 'asc']],
                    info: false,
                    searching: false,
                    processing: true,
                    ajax: {
                        url: "{{ route('exchange.get-orders') }}",
                        data: {
                            last_price: null,
                            order_type: function () {
                                return "{{ ORDER_TYPE_SELL }}";
                            },
                            coin_pair: function () {
                                return currentInstance.pairDetail.tradePair;
                            }
                        },
                        dataSrc: function (data) {
                            currentInstance.askOrderDetail.totalTradeCoinAskedOrder = bcmul(data.totalCoinOrder.trade_coin_total, 1, 8);
                            return data.coinOrders;
                        }
                    },
                    columns: [
                        {data: "price"},
                        {data: "amount"},
                        {data: "total"}
                    ],
                    scrollY: 392,
                    columnDefs: [
                        {
                            targets: '_all',
                            createdCell: function (td) {
                                $(td).css('padding', '2px 10px');
                            },
                            orderable: false
                        },
                        {
                            targets: 0,
                            className: 'text-pink w-30'
                        },
                        {
                            targets: 1,
                            className: 'text-right w-30'
                        },
                        {
                            targets: 2,
                            className: 'text-right w-30'
                        }
                    ],
                    createdRow: function (row, data) {
                        $(row).attr('id', currentInstance.hash(data.price));
                    },
                    drawCallback: function () {
                        const api = this.api();
                        api.rows().every(function (rowId) {
                            const data = this.data();
                            if (rowId === 0) {
                                currentInstance.currentLowestPrice = data.price;
                                currentInstance.limitBuyFormData.price = currentInstance.currentLowestPrice;
                                currentInstance.stopLimitBuyFormData.price = currentInstance.currentLowestPrice;
                                currentInstance.stopLimitBuyFormData.stop = currentInstance.currentLowestPrice;
                            }
                        });
                    }
                });

                $(document).find('#ask-order-table tbody').on('click', 'tr', function () {
                    const tableData = currentInstance.askOrderTableInstance.row(this).data();

                    if (tableData) {
                        currentInstance.resetLimitSellPrice();
                        currentInstance.resetLimitBuyPrice();
                        currentInstance.setLimitBuyPrice(tableData.price);
                        currentInstance.setLimitBuyAmount(tableData.amount);
                        currentInstance.setLimitBuyTotal(tableData.total);
                        currentInstance.setStopLimitValue(tableData.price);

                        setTimeout(function() {
                            limitBuyForm.reFormat();
                            stopLimitBuyForm.reFormat();
                        }, 100);
                    }
                });

                currentInstance.initScrollBar();
            },
            initBidOrderTable: function () {
                const currentInstance = this;
                currentInstance.bidOrderTableInstance = $(document).find('#bid-order-table').DataTable({
                    paging: false,
                    order: [[0, 'desc']],
                    info: false,
                    searching: false,
                    processing: true,
                    ajax: {
                        url: "{{ route('exchange.get-orders') }}",
                        data: {
                            last_price: null,
                            order_type: function () {
                                return "{{ ORDER_TYPE_BUY }}";
                            },
                            coin_pair: function () {
                                return currentInstance.pairDetail.tradePair;
                            }
                        },
                        dataSrc: function (data) {
                            currentInstance.bidOrderDetail.totalBaseCoinBidOrder = bcmul(data.totalCoinOrder.base_coin_total, 1, 8);
                            return data.coinOrders;
                        }
                    },
                    columns: [
                        {data: "price"},
                        {data: "amount"},
                        {data: "total"}
                    ],
                    scrollY: 392,
                    columnDefs: [
                        {
                            targets: '_all',
                            createdCell: function (td) {
                                $(td).css('padding', '2px 10px');
                            },
                            orderable: false
                        },
                        {
                            targets: 0,
                            className: 'text-green w-30'
                        },
                        {
                            targets: 1,
                            className: 'text-right w-30'
                        },
                        {
                            targets: 2,
                            className: 'text-right w-30'
                        }
                    ],
                    createdRow: function (row, data) {
                        $(row).attr('id', currentInstance.hash(data.price));
                    },
                    drawCallback: function () {
                        const api = this.api();
                        api.rows().every(function (rowId) {
                            const data = this.data();
                            if (rowId === 0) {
                                currentInstance.currentHighestPrice = data.price;
                                currentInstance.limitSellFormData.price = currentInstance.currentHighestPrice;
                                currentInstance.stopLimitSellFormData.price = currentInstance.currentHighestPrice;
                                currentInstance.stopLimitSellFormData.stop = currentInstance.currentHighestPrice;
                            }
                        });
                    }
                });

                $(document).find('#bid-order-table tbody').on('click', 'tr', function () {
                    const tableData = currentInstance.bidOrderTableInstance.row(this).data();

                    let test = 0;

                    if (tableData) {
                        currentInstance.resetLimitBuyPrice();
                        currentInstance.resetLimitSellPrice();
                        currentInstance.setLimitSellPrice(tableData.price);
                        currentInstance.setLimitSellAmount(tableData.amount);
                        currentInstance.setLimitSellTotal(tableData.total);
                        currentInstance.setStopLimitValue(tableData.price);

                        setTimeout(function() {
                            limitSellForm.reFormat();
                            stopLimitSellForm.reFormat();
                        }, 100);
                    }
                });

                currentInstance.initScrollBar();
            },
            initMarketHistoryTable() {
                const currentInstance = this;
                currentInstance.marketTradeHistoryTableInstance = $(document).find('#market-trade-history-table').DataTable({
                    paging: false,
                    order: [[2, 'desc']],
                    info: false,
                    searching: false,
                    processing: true,
                    ajax: {
                        url: '{{ route('exchange.get-trade-histories') }}',
                        data: {
                            coin_pair: function () {
                                return currentInstance.pairDetail.tradePair;
                            }
                        },
                        dataSrc: function (data) {
                            return data;
                        }
                    },
                    columns: [
                        {
                            data: "price",
                            render: function (data, type, row) {
                                let colorClass = row.order_type === "{{ ORDER_TYPE_BUY }}" ? 'text-green' : 'text-pink';
                                return '<span class="' + colorClass + '">' + data + '</span>';
                            },
                            orderable: false
                        },
                        {
                            data: "amount",
                            orderable: false
                        },
                        {
                            data: "date",
                            orderable: false,
                            render: function (data, type, row) {
                                return moment(data).format("HH:mm:ss");
                            }
                        }
                    ],
                    scrollY: 394,
                    columnDefs: [
                        {
                            targets: '_all',
                            createdCell: function (td) {
                                $(td).css('padding', '2px 10px');
                            }
                        },
                        {
                            targets: 0,
                            className: 'w-30'
                        },
                        {
                            targets: 1,
                            className: 'text-right w-30'
                        },
                        {
                            targets: 2,
                            className: 'text-right w-30'
                        }
                    ],
                });

                currentInstance.initScrollBar();
            },
            changeUrl() {
                const url = "{{ route('exchange.index') }}/" + this.pairDetail.tradePair;
                window.history.pushState({}, null, url);
            },
            initScrollBar() {
                let scrollDom = $(document).find(".dataTables_scrollBody");
                scrollDom.mCustomScrollbar("destroy");
                scrollDom.mCustomScrollbar({
                    axis: "y",
                    theme: "minimal",
                });
            },
            initChart(isStart) {
                const currentInstance = this;
                axios({
                    method: 'get',
                    url: "{{ url('api/public') }}",
                    params: {
                        command: 'returnChartData',
                        tradePair: currentInstance.pairDetail.tradePair,
                        start: currentInstance.chartOptions.start
                    }
                })
                    .then((response) => {
                        initialData = response.data;
                        currentInstance.chartOptions.separator = currentInstance.chartOptions.separator === '-' ? '−' : '-';
                        const coinStructure = currentInstance.pairDetail.tradeCoin + currentInstance.chartOptions.separator + currentInstance.pairDetail.baseCoin;

                        if (isStart) {
                            initTradingView(
                                currentInstance.chartOptions.interval,
                                coinStructure,
                                currentInstance.chartOptions.chartColor,
                                currentInstance.chartOptions.rootPath
                            );
                        } else {
                            window.tvWidget.chart().setSymbol(coinStructure);
                        }
                    });
            },
            initChartOptions() {
                this.chartOptions.rootPath = "{{ asset('/') }}";

                @if (isset($_COOKIE['style']) &&  $_COOKIE['style'] == 'light')
                    this.chartOptions.chartColor = {
                    theme: 'light',
                    bg: '#ffffff',
                    front: '#2AA3A3',
                    candleUp: '#2AA3A3',
                    candleDown: '#E2595B',
                    gridColor: '#DFDFDF',
                    crossHair: '#999999',
                    textColor: '#31353F',
                    lineColor: '#DFDFDF'
                };
                @else
                    this.chartOptions.chartColor = {
                    theme: 'dark',
                    bg: '#262626',
                    front: '#839cff',
                    candleUp: '#83c700',
                    candleDown: '#ef055c',
                    gridColor: '#313131',
                    crossHair: '#eeeeee',
                    textColor: '#9db2bd',
                    lineColor: '#313131'
                };
                @endif
            },
            _addDataTableRow(data, tableInstance) {
                const row = tableInstance.row.add(data).draw().node();
                const className = data.type === "{{ ORDER_TYPE_BUY }}" ? 'inserted-buy' : 'inserted-sell';
                this.rowHighlight(row, className);
            },
            _updateOrderTable(data, addRow) {
                let orderTableInstance;
                if (data.type === "{{ ORDER_TYPE_SELL }}") {
                    orderTableInstance = this.askOrderTableInstance
                    this.askOrderDetail.totalTradeCoinAskedOrder = bcadd(this.askOrderDetail.totalTradeCoinAskedOrder, data.amount, 8);
                } else {
                    orderTableInstance = this.bidOrderTableInstance;
                    this.bidOrderDetail.totalBaseCoinBidOrder = bcadd(this.bidOrderDetail.totalBaseCoinBidOrder, data.total, 8);
                }

                let row = orderTableInstance.row("#" + this.hash(data.price));
                let rowData = row.data();

                if (rowData) {
                    rowData.amount = bcadd(rowData.amount, data.amount, 8);
                    rowData.total = bcadd(rowData.total, data.total, 8);

                    if (bccomp(rowData.amount, '0', 8) > 0) {
                        row.data(rowData).invalidate();
                        this.rowHighlight(row.node(), 'updated');
                    } else {
                        orderTableInstance.rows(row).remove();
                    }

                    orderTableInstance.draw();
                } else if (addRow) {
                    data.price = bcmul(data.price, 1, 8);
                    data.amount = bcmul(data.amount, 1, 8);
                    data.total = bcmul(data.total, 1, 8);

                    this._addDataTableRow(data, orderTableInstance);
                }
            },
            updateOrderTableOnOrderCreatedBroadCast(data) {
                this._updateOrderTable(data, true);
            },

            updateOrderTableOnOrderCanceledBroadCast(data) {
                if (data.category !== "{{ ORDER_CATEGORY_MARKET }}" && data.previous_status === "{{ STATUS_PENDING }}") {
                    data.amount = bcmul(data.amount, -1, 8);
                    data.total = bcmul(data.total, -1, 8);
                    this._updateOrderTable(data, false);
                }
            },
            updateOrderTableOnOrderSettlementBroadCast(data) {
                const currentInstance = this;
                data.map(function (orderData) {
                    let cloneData = {...orderData};
                    cloneData.previous_status = "{{ STATUS_PENDING }}";
                    // cancel and settlement behave almost same.
                    currentInstance.updateOrderTableOnOrderCanceledBroadCast(cloneData);
                });
            },
            updatePublicTablesOnOrderExchangedBroadCast(data) {
                const currentInstance = this;
                data.map(function (tradeHistory) {
                    let refactorTradeHistoryData = {
                        ...tradeHistory,
                        amount: bcmul(tradeHistory.amount, -1, 8),
                        total: bcmul(tradeHistory.total, -1, 8)
                    };

                    if (tradeHistory.category !== "{{ ORDER_CATEGORY_MARKET }}") {
                        currentInstance._updateOrderTable(refactorTradeHistoryData, false);
                    }

                    if (tradeHistory.is_maker) {

                        let maketTradeHistory = {...tradeHistory};

                        //update chart data
                        currentInstance.updateChart(maketTradeHistory);
                        //update market trade history table
                        maketTradeHistory.order_type = maketTradeHistory.type;
                        maketTradeHistory.date = moment.unix(maketTradeHistory.date).format("YYYY-MM-DD HH:mm:ss");
                        let row = currentInstance.marketTradeHistoryTableInstance.row.add(maketTradeHistory).draw().node();
                        let className = maketTradeHistory.type === "{{ ORDER_TYPE_BUY }}" ? 'inserted-buy' : 'inserted-sell';
                        currentInstance.rowHighlight(row, className);
                        // update exchange summary
                        let summaryData = {
                            trade_pair_name: currentInstance.pairDetail.name,
                            trade_pair: currentInstance.pairDetail.tradePair,
                            trade_coin_name: currentInstance.pairDetail.tradeCoinName,
                            trade_coin: currentInstance.pairDetail.tradeCoin,
                            trade_coin_icon: currentInstance.pairDetail.tradeCoinIcon,
                            base_coin: currentInstance.pairDetail.baseCoin,
                            base_coin_icon: currentInstance.pairDetail.baseCoinIcon,
                            latest_price: maketTradeHistory.price,
                            change: bcmul(bcdiv(bcsub(maketTradeHistory.price, currentInstance.pairDetail.lastPrice, 8), currentInstance.pairDetail.lastPrice, 8), 100, 2),
                            high_price: maketTradeHistory.price > currentInstance.pairDetail.high24hr ? maketTradeHistory.price : currentInstance.pairDetail.high24hr,
                            low_price: currentInstance.pairDetail.low24hr > maketTradeHistory.price ? maketTradeHistory.price : currentInstance.pairDetail.low24hr,
                            base_coin_volume: bcadd(currentInstance.pairDetail.baseCoinVolume, maketTradeHistory.total, 8),
                            trade_coin_volume: bcadd(currentInstance.pairDetail.tradeCoinVolume, maketTradeHistory.amount, 8)
                        }
                        currentInstance.updateCoinPair24hrSummary(summaryData);
                        // update market price
                        let marketRow = currentInstance.marketTableInstance.row("#market-" + currentInstance.pairDetail.tradePair);
                        let marketData = marketRow.data();

                        if (marketData) {
                            marketData.latest_price = summaryData.latest_price;
                            marketData.base_coin_volume = summaryData.base_coin_volume;
                            marketData.change = summaryData.change;
                            marketRow.data(marketData).invalidate();
                            currentInstance.rowHighlight(marketRow.node(), 'updated');
                            currentInstance.marketTableInstance.draw();
                        }
                    }
                });
            },
            rowHighlight(row, className, animationTime = 1000) {
                $(row).addClass(className);
                const autoTransition = () => $(row).removeClass(className);
                setTimeout(autoTransition, animationTime);
            },
            hash(number) {
                number = bcmul(number, 1, 8);
                return number.replace(/[0-9]|\./gi, (x) => {
                    const characters = ['z', 'o', 't', 'r', 'f', 'i', 's', 'e', 'g', 'n', 'x'];
                    return x === '.' ? '' : characters[x];
                });
            },
            getPriceTolerance(price) {
                price = price ?? this.pairDetail.lastPrice;
                let tolerance = bcdiv(bcmul(price, this.settingTolerance, 8), "100", 8);
                let highTolerance = bcadd(price, tolerance, 8);
                let lowTolerance = bcsub(price, tolerance, 8);

                return [lowTolerance, highTolerance];
            },
            unsubscribeOrderBroadcast() {
                Echo.leave(this.orderBroadcastChannel);
            },
            subscribeOrderBroadcast() {
                const currentInstance = this;

                Echo.channel(currentInstance.orderBroadcastChannel)
                    .listen('.order.created', function (data) {
                        currentInstance.updateOrderTableOnOrderCreatedBroadCast(data);
                    })
                    .listen('.order.canceled', function (data) {
                        currentInstance.updateOrderTableOnOrderCanceledBroadCast(data);
                    })
                    .listen('.order.exchanged', function (data) {
                        if(_.isObject(data)){
                            data = _.compact(_.values(data))
                        }
                        currentInstance.updatePublicTablesOnOrderExchangedBroadCast(data);
                        @auth
                        currentInstance.updateUserTablesOnOrderExchangedBroadCast(data);
                        @endauth
                    })
                    .listen('.order.settlement', function (data) {
                        currentInstance.updateOrderTableOnOrderSettlementBroadCast(data);
                        @auth
                        currentInstance.updateUserTablesOnOrderSettlementBroadCast(data);
                        @endauth
                    });
            },
            updateChart(data) {
                const currentInstance = this;
                let lastChartData = initialData.slice(-1)[0];
                const currentChartIntervalDate = parseInt(data.date / this.chartOptions.interval) * this.chartOptions.interval;

                if (lastChartData && lastChartData['date'] === currentChartIntervalDate) {
                    lastChartData['close'] = data.price;
                    lastChartData['volume'] = bcadd(lastChartData['volume'], data.amount, 8);

                    if (bccomp(data.price, lastChartData['high'], 8) > 0) {
                        lastChartData['high'] = data.price;
                    }

                    if (lastChartData['low'] > data.price) {
                        lastChartData['low'] = data.price;
                    }
                } else {
                    initialData.push({
                        date: currentChartIntervalDate,
                        close: data.price,
                        high: data.price,
                        low: data.price,
                        open: data.price,
                        volume: data.amount
                    });
                }

                currentInstance.chartOptions.separator = currentInstance.chartOptions.separator === '-' ? '−' : '-';
                const coinStructure = currentInstance.pairDetail.tradeCoin + currentInstance.chartOptions.separator + currentInstance.pairDetail.baseCoin;
                window.tvWidget.chart().setSymbol(coinStructure);
            },
            @auth
            decreaseUserBalance(balanceField, amount) {
                this.user[balanceField] = bcsub(this.user[balanceField], amount, 8);
            },
            increaseUserBalance(balanceField, amount) {
                this.user[balanceField] = bcadd(this.user[balanceField], amount, 8);
            },
            initUserWalletSummary() {
                const currentInstance = this;
                currentInstance.user = {
                    ...currentInstance.user,
                    tradeCoinBalance: bcmul(0, 1, 8),
                    baseCoinBalance: bcmul(0, 1, 8)
                };
                axios({
                    method: 'get',
                    url: "{{ route('exchange.get-wallet-summary') }}",
                    params: {
                        coin_pair: currentInstance.pairDetail.tradePair
                    }
                })
                    .then((response) => {
                        currentInstance.user = {
                            ...currentInstance.user,
                            tradeCoinBalance: bcmul(response.data.trade_coin_balance, 1, 8),
                            baseCoinBalance: bcmul(response.data.base_coin_balance, 1, 8)
                        };
                    });
            },
            initUserOpenOrderTable() {
                const currentInstance = this;
                currentInstance.userOpenOrderTableInstance = $(document).find('#user-open-order-table').DataTable({
                    paging: false,
                    info: false,
                    order: [[0, 'desc']],
                    searching: false,
                    responsive: true,
                    processing: true,
                    ajax: {
                        url: '{{ route('exchange.get-my-open-orders') }}',
                        data: {
                            coin_pair: function () {
                                return currentInstance.pairDetail.tradePair;
                            }
                        },
                        dataSrc: function (data) {
                            return data;
                        }
                    },
                    columns: [
                        {
                            data: "date"
                        },
                        {
                            data: "order_type",
                            render: function (data, type, row) {
                                let colorClass = row.order_type === "{{ ORDER_TYPE_BUY }}" ? 'text-green' : 'text-pink';
                                return '<span class="text-uppercase ' + colorClass + '">' + data + '</span>';
                            }
                        },
                        {
                            data: "price",
                            render: function (data, type, row) {
                                return bcmul(data, 1, 8);
                            }
                        },
                        {
                            data: "amount",
                            render: function (data, type, row) {
                                return bcmul(data, 1, 8);
                            }
                        },
                        {
                            data: "open_amount",
                            render: function (data, type, row) {
                                return bcmul(data, 1, 8);
                            }
                        },
                        {
                            data: "exchanged",
                            render: function (data, type, row) {
                                return bcmul(data, 1, 8);
                            }
                        },
                        {
                            data: "total",
                            render: function (data, type, row) {
                                return bcmul(data, 1, 8);
                            }
                        },
                        {
                            data: "stop_limit",
                            render: function (data, type, row) {
                                return row.stop_limit != null ? bcmul(row.stop_limit, 1, 8) : '-';
                            }
                        },
                        {
                            data: null,
                            render: function (data) {
                                const url = currentInstance.orderCancelUrl.replace('##', data.order_id);
                                return '<button class="text-danger cancel_order btn btn-link p-0 m-0"' +
                                    ' data-id="' + data.order_id + '"' +
                                    ' data-url="' + url + '">{{ __('Cancel') }}</button>';
                            }
                        }
                    ],
                    columnDefs: [
                        {
                            targets: 0,
                            className: 'text-left p-1 border-top lf-toggle-border-color align-middle'
                        },
                        {
                            targets: 1,
                            className: 'text-left p-1 border-left border-top lf-toggle-border-color align-middle'
                        },
                        {
                            targets: '_all',
                            className: 'text-right p-1 border-left border-top lf-toggle-border-color align-middle',
                            orderable: false
                        }
                    ],
                    createdRow: function (row, data) {
                        $(row).attr('id', data.order_id);
                    },
                });
            },
            initUserTradeHistoryTable() {
                const currentInstance = this;
                currentInstance.userTraderHistoryTableInstance = $(document).find('#user-trade-history-table').DataTable({
                    paging: false,
                    info: false,
                    order: [[0, 'desc']],
                    searching: false,
                    responsive: true,
                    processing: true,
                    ajax: {
                        url: '{{ route('exchange.get-my-trades') }}',
                        data: {
                            coin_pair: function () {
                                return currentInstance.pairDetail.tradePair;
                            }
                        },
                        dataSrc: function (data) {
                            return data;
                        }
                    },
                    columns: [
                        {data: "date"},
                        {
                            data: "order_type",
                            render: function (data, type, row) {
                                let colorClass = row.order_type === "{{ ORDER_TYPE_BUY }}" ? 'text-green' : 'text-pink';
                                return '<span class="text-uppercase ' + colorClass + '">' + data + '</span>';
                            }
                        },
                        {data: "price"},
                        {data: "amount"},
                        {data: "total"}
                    ],
                    columnDefs: [
                        {
                            targets: 0,
                            className: 'text-left p-1 border-top lf-toggle-border-color'
                        },
                        {
                            targets: 1,
                            className: 'text-left p-1 border-left border-top lf-toggle-border-color'
                        },
                        {
                            targets: '_all',
                            className: 'text-right p-1 border-left border-top lf-toggle-border-color',
                            orderable: false
                        }
                    ]
                });
            },
            placeOrder(categoryType, orderType, formInstance) {
                const currentInstance = this;
                const formDataKey = _.camelCase(categoryType + '_' + orderType + '_FormData');
                currentInstance[formDataKey].placingOrder = true;

                if (eval(formInstance).getErrorMessage().length > 0) {
                    currentInstance[formDataKey].placingOrder = false;
                    return;
                }

                const formData = {
                    ...currentInstance[formDataKey],
                    category: categoryType,
                    order_type: orderType,
                    trade_pair: currentInstance.pairDetail.tradePair
                }
                // checking balance
                let balance = currentInstance.user.tradeCoinBalance;
                let comparableBalance = formData.amount;

                if (formData.order_type === "{{ ORDER_TYPE_BUY }}") {
                    balance = currentInstance.user.baseCoinBalance;
                    comparableBalance = formData.total
                }

                if (bccomp(balance, comparableBalance, 8) < 0) {
                    currentInstance[formDataKey].placingOrder = false;
                    flashBox('error', "{{__('You don\'t have enough balance to place order.')}}");
                    return;
                }

                delete formData.placingOrder;
                const route = "{{ route('user.order.store') }}";

                axios.post(route, formData)
                    .then((response) => {
                        let amount, balanceField;

                        if (formData.order_type === '{{ ORDER_TYPE_BUY }}') {
                            amount = response.data.data.total;
                            balanceField = 'baseCoinBalance';
                            currentInstance.resetLimitBuyPrice();
                            currentInstance.resetStopLimitBuyPrice();
                            currentInstance.resetMarketBuyForm();
                        } else {
                            amount = response.data.data.amount;
                            balanceField = 'tradeCoinBalance';
                            currentInstance.resetLimitSellPrice();
                            currentInstance.resetStopLimitSellPrice();
                            currentInstance.resetMarketSellForm();
                        }

                        currentInstance.decreaseUserBalance(balanceField, amount);

                        if (response.data.data.category !== "{{ ORDER_CATEGORY_MARKET }}") {
                            currentInstance.updateUserOpenOrderTableOnOrderPlaceBroadCast(response.data.data);
                        }

                        flashBox('success', response.data.message);
                    })
                    .catch((error) => {
                        let message = "{{ __('Failed to place the order! Please try again.') }}";

                        if (error.response.status === 400 || error.response.status === 422) {
                            message = error.response.data.message;
                            // TODO::show form error data if error status 422
                        }

                        flashBox('error', message);
                    })
                    .finally(() => {
                        currentInstance[formDataKey].placingOrder = false;
                    });
            },
            updateUserOpenOrderTableOnOrderPlaceBroadCast(data) {
                this._addDataTableRow(data, this.userOpenOrderTableInstance);
            },
            cancelOrder(orderId, url, buttonInstance) {
                const currentInstance = this;

                axios({
                    method: 'delete',
                    url: url
                }).then((response) => {
                    const canceledRow = currentInstance.userOpenOrderTableInstance.row('#' + orderId);
                    const canceledRowData = canceledRow.data();
                    let balanceField = 'tradeCoinBalance';
                    let amount = canceledRowData.open_amount;

                    if (canceledRowData.order_type === "{{ ORDER_TYPE_BUY }}") {
                        balanceField = 'baseCoinBalance';
                        amount = bcmul(canceledRowData.open_amount, canceledRowData.price, 8);
                    }
                    // return balance to user
                    currentInstance.increaseUserBalance(balanceField, amount);
                    // remove row from user open order tables
                    currentInstance.userOpenOrderTableInstance.rows(canceledRow).remove();
                    currentInstance.userOpenOrderTableInstance.draw();
                    flashBox('success', response.data.message);
                }).catch((error) => {
                    let message = error.response ? error.response.data.message : "{{ __('Something went wrong! Please try again.') }}";
                    flashBox('error', message);
                }).finally(() => {
                    buttonInstance.html(buttonInstance.data('original-text'));
                    buttonInstance.removeAttr('disabled');
                });
            },
            _updateUserOpenOrderTableOnOrderExchangedBroadCast(data) {
                let row = this.userOpenOrderTableInstance.row("#" + data.order_id);
                let rowData = row.data();

                if (rowData) {
                    rowData.open_amount = bcsub(rowData.open_amount, data.amount, 8);
                    rowData.exchanged = bcadd(rowData.exchanged, data.amount, 8);

                    if (bccomp(rowData.open_amount, '0', 8) > 0) {
                        row.data(rowData).invalidate();
                        this.rowHighlight(row.node(), 'updated');
                    } else {
                        this.userOpenOrderTableInstance.rows(row).remove();
                    }

                    this.userOpenOrderTableInstance.draw();
                }
            },
            updateUserTablesOnOrderExchangedBroadCast(data) {
                const currentInstance = this;
                const userTradeHistories = data.filter(function (tradeHistory) {
                    return tradeHistory.user_id === "{{ auth()->id() }}";
                });

                if (userTradeHistories.length > 0) {
                    userTradeHistories.map(function (userTradeHistory) {
                        let refactorUserTradeHistory = {...userTradeHistory};

                        refactorUserTradeHistory.order_type = refactorUserTradeHistory.type;
                        refactorUserTradeHistory.date = moment.unix(refactorUserTradeHistory.date).format("YYYY-MM-DD HH:mm:ss");
                        currentInstance._addDataTableRow(refactorUserTradeHistory, currentInstance.userTraderHistoryTableInstance);
                        currentInstance._updateUserOpenOrderTableOnOrderExchangedBroadCast(refactorUserTradeHistory);

                        let balanceField = 'tradeCoinBalance';
                        let amount = bcsub(refactorUserTradeHistory.amount, refactorUserTradeHistory.fee, 8);

                        if (refactorUserTradeHistory.type === "{{ ORDER_TYPE_SELL }}") {
                            balanceField = 'baseCoinBalance';
                            amount = bcsub(refactorUserTradeHistory.total, refactorUserTradeHistory.fee, 8);
                        }
                        currentInstance.increaseUserBalance(balanceField, amount);
                    });
                }
            },
            updateUserTablesOnOrderSettlementBroadCast(data) {
                const currentInstance = this;
                let userOrders = data.filter(function (userOrder) {
                    return userOrder.user_id === "{{ auth()->id() }}";
                });

                if (userOrders.length > 0) {
                    userOrders.map(function (userOrder) {
                        let cloneUserOrder = {...userOrder};
                        // this sub function serves for both exhanged and settlement events.
                        currentInstance._updateUserOpenOrderTableOnOrderExchangedBroadCast(cloneUserOrder);

                        let balanceField = 'tradeCoinBalance';
                        let amount = cloneUserOrder.amount;

                        if (cloneUserOrder.type === "{{ ORDER_TYPE_BUY }}") {
                            balanceField = 'baseCoinBalance';
                            amount = cloneUserOrder.total;
                        }

                        if (bccomp(amount, '0', 8) > 0) {
                            currentInstance.increaseUserBalance(balanceField, amount);
                        }
                    });
                }
            },
            @endauth
        },
        mounted() {
            this.initChartOptions();
        }
    });
</script>
