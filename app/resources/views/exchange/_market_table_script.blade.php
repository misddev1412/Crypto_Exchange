<script>
    "use strict";

    $(function () {
        vueInstance.marketTableInstance = $('#market-table').DataTable({
            destroy: true,
            paging: false,
            info: false,
            order: [[0, 'asc']],
            select: {
                style: 'single',
                selector: 'tr:not(.selected)'
            },
            scrollY: 448,
            language: {search: "", searchPlaceholder: "{{ __('Search') }}"},
            processing: true,
            ajax: {
                url: '{{ route('exchange.get-coin-market', $coinPair->base_coin) }}',
                dataSrc: function (json) {
                    return json.coin_pairs;
                }
            },
            columnDefs: [
                {
                    targets: '_all',
                    createdCell: function (td) {
                        $(td).css('padding', '5px');
                    }
                },
                {
                    targets: 0,
                    className: 'text-green w-30'
                },
                {
                    targets: 1,
                    className: 'text-center w-40'
                },
                {
                    targets: 2,
                    className: 'text-right w-30'
                },
                {
                    targets: 3,
                    className: 'text-right w-30',
                    visible: false
                }
            ],
            columns: [
                {
                    data: "trade_coin",
                    width: "30%"
                },
                {
                    data: "latest_price",
                },
                {
                    data: "base_coin_volume",
                    render: function (data) {
                        return bcmul(data, 1, 3)
                    }
                },
                {
                    data: "change",
                    render: function (data) {
                        let value = bcmul(Math.abs(data), 1, 2);

                        if (parseFloat(data) > 0) {
                            return '<span class="text-success">' + value + '%</span>';
                        } else if (parseFloat(data) < 0) {
                            return '<span class="text-danger">-' + value + '%</span>';
                        } else {
                            return '<span class="lf-toggle-text-color-50">' + value + '%</span>';
                        }
                    }
                },
                {
                    data: "base_coin",
                    visible: false
                }
            ],
            initComplete: function (_, json) {
                if (json.base_coins) {
                    let defaultDropdownItem = '';
                    let dropdownItems = '';
                    let dropDown = '<div class="dropdown" id="market-table-dropdown">';
                    $.each(json.base_coins, function (baseCoin, baseCoinInfo) {
                        if (vueInstance.pairDetail.baseCoin === baseCoin) {
                            defaultDropdownItem += '<a href="javascript:;"' +
                                ' class="dropdown-toggle lf-toggle-text-color text-center font-size-16 font-weight-bold"\n' +
                                ' data-toggle="dropdown"\n' +
                                ' aria-haspopup="true" aria-expanded="false"\n' +
                                ' >\n' +
                                '<img src="' + baseCoinInfo.icon_url + '" class="lf-w-16px"> ' + baseCoin +
                                '</a>';
                        }

                        dropdownItems += '<button class="dropdown-item" type="button" data-url="' + baseCoinInfo.market_url + '">' +
                            '<img src="' + baseCoinInfo.icon_url + '" class="lf-w-16px"> ' + baseCoin +
                            '</button>';
                    });
                    dropDown += defaultDropdownItem;
                    dropDown += '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">';
                    dropDown += dropdownItems;
                    dropDown += '</div></div>';

                    $("#market-table-dropdown-wrapper").html(dropDown);
                }

                const selectedRowData = vueInstance.marketTableInstance.row({selected: true}).data();
                vueInstance.updateCoinPair24hrSummary(selectedRowData);
                vueInstance.initAskOrderTable();
                vueInstance.initBidOrderTable();
                vueInstance.initMarketHistoryTable();
                vueInstance.changeUrl();
                vueInstance.initChart(true);
                vueInstance.subscribeOrderBroadcast();
                @auth
                vueInstance.initUserWalletSummary();
                vueInstance.initUserOpenOrderTable();
                vueInstance.initUserTradeHistoryTable();
                @endauth
            },
            rowCallback: function (row, data) {
                if (data.trade_pair === vueInstance.pairDetail.tradePair) {
                    vueInstance.marketTableInstance.row(row).select();
                }

                $(row).attr('id', 'market-' + data.trade_pair);
            },
        });

        // filter style
        $('#market-table_wrapper .dataTables_filter').addClass('d-flex justify-content-between');
        $('#market-table_wrapper .dataTables_filter input[type=search]').addClass('form-control font-size-12 lf-h-30px');
        $('#market-table_wrapper .dataTables_filter > label').addClass('search-label');

        $(
            '<div class="d-inline-block">\n' +
            '    <form autocomplete="off">\n' +
            '        <label class="custom-radio-box d-inline-block mr-3 font-size-12">\n' +
            '            VOL.\n' +
            '            <input type="radio" name="column-filter" value="2" checked="checked">\n' +
            '            <span class="checkmark"></span>\n' +
            '        </label>\n' +
            '\n' +
            '        <label class="custom-radio-box d-inline-block font-size-12">\n' +
            '            CHNG.\n' +
            '            <input type="radio" name="column-filter" value="3">\n' +
            '            <span class="checkmark"></span>\n' +
            '        </label>\n' +
            '    </form>\n' +
            '</div>'
        ).appendTo('#market-table_wrapper .dataTables_filter');
        // filtering column
        $(document).on('click', 'input[name="column-filter"]', function (event) {
            const selectedColumnIndex = parseInt(event.target.value);
            const volumeColumn = vueInstance.marketTableInstance.column(2);
            const changeColumn = vueInstance.marketTableInstance.column(3);
            volumeColumn.visible(selectedColumnIndex === 2);
            changeColumn.visible(selectedColumnIndex === 3);
        });
        // filtering market based on base coin.
        $(document).on('click', '#market-table-dropdown .dropdown-item', function () {
            const selectedText = $(this).html();
            $(this).parents('#market-table-dropdown').find('.dropdown-toggle').html(selectedText + '<span class="caret"></span>');
            const marketUrl = $(this).data('url');
            vueInstance.marketTableInstance.ajax.url(marketUrl).load();
        });

        // select row
        vueInstance.marketTableInstance.on('user-select', function (e, dt, type, cell, originalEvent) {
            if (type === 'row') {
                const row = dt.row(originalEvent.currentTarget);
                const selectedRowData = row.data();

                vueInstance.unsubscribeOrderBroadcast();
                vueInstance.currentLowestPrice = null;
                vueInstance.currentHighestPrice = null;
                vueInstance.updateCoinPair24hrSummary(selectedRowData);
                vueInstance.resetLimitSellPrice();
                vueInstance.resetLimitBuyPrice();
                vueInstance.resetMarketBuyForm();
                vueInstance.resetMarketSellForm();
                vueInstance.resetStopLimitSellPrice();
                vueInstance.resetStopLimitBuyPrice();

                vueInstance.askOrderTableInstance.ajax.reload();
                vueInstance.bidOrderTableInstance.ajax.reload();
                vueInstance.marketTradeHistoryTableInstance.ajax.reload();
                vueInstance.initScrollBar();
                vueInstance.changeUrl();
                vueInstance.initChart(true);
                vueInstance.subscribeOrderBroadcast();
                @auth
                vueInstance.initUserWalletSummary();
                vueInstance.userOpenOrderTableInstance.ajax.reload();
                vueInstance.userTraderHistoryTableInstance.ajax.reload();
                @endauth
            }
        });

        vueInstance.initScrollBar();
    });
</script>
