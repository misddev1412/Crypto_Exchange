"use strict";

let initialDataFeed = new Datafeeds.TradingViewDatafeed();
let initialData = vueInstance.chartOptions.initialData;
let intervalLoader = 5;

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    let regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function initTradingView(currentInterval, coinStructure, chartColor, rootPath) {
    intervalLoader = currentInterval / 60;
    intervalLoader = parseInt(intervalLoader);

    let widget = window.tvWidget = new TradingView.widget({
        debug: false,
        // fullscreen: true,
        autosize: true,
        height: 460,
        width: '100%',
        symbol: coinStructure,
        interval: intervalLoader,
        charts_storage_api_version: "1.1",
        client_id: 'tradingview.com',
        theme: chartColor.theme,
        container_id: "tv_chart_container",
        datafeed: initialDataFeed,
        library_path: rootPath + "plugins/charting_library/",
        custom_css_url: rootPath + "plugins/charting_library/style.css",
        locale: getParameterByName('lang') || "en",
        allow_symbol_change: false,
        disabled_features: [
            "header_widget_dom_node",
            "use_localstorage_for_settings",
            "header_symbol_search",
            // "timeframes_toolbar",
            // "header_resolutions",
            // "header_interval_dialog_button",
            // "trading_notifications",
            "border_around_the_chart",
            // "header_chart_type",
            "header_screenshot",
            "header_compare",
            "header_settings",
            "footer_widget",
            "compare_symbol",
            "go_to_date",
            "remove_library_container_border",
            "border_around_the_chart",
        ],
        enabled_features: [
            "hide_left_toolbar_by_default",
            "move_logo_to_main_pane"
            // "same_data_requery"
        ],
        user_id: 'public_user_id',
        toolbar_bg: chartColor.bg,
        loading_screen: {
            backgroundColor: chartColor.bg,
            foregroundColor: chartColor.front
        },
        overrides: {
            "paneProperties.background": chartColor.bg,
            "paneProperties.vertGridProperties.color": chartColor.gridColor,
            "paneProperties.horzGridProperties.color": chartColor.gridColor,

            "paneProperties.crossHairProperties.color": chartColor.crossHair,
            "scalesProperties.backgroundColor": chartColor.bg,
            "scalesProperties.textColor": chartColor.textColor,
            "scalesProperties.lineColor": chartColor.lineColor,
            // Candles styles
            "mainSeriesProperties.candleStyle.wickUpColor": chartColor.candleUp,
            "mainSeriesProperties.candleStyle.wickDownColor": chartColor.candleDown,
            "mainSeriesProperties.candleStyle.upColor": chartColor.candleUp,
            "mainSeriesProperties.candleStyle.downColor": chartColor.candleDown,
            "mainSeriesProperties.candleStyle.borderUpColor": chartColor.candleUp,
            "mainSeriesProperties.candleStyle.borderDownColor": chartColor.candleDown,

            "paneProperties.legendProperties.showLegend": false,
            "paneProperties.legendProperties.showStudyValues": false,

            "symbolWatermarkProperties.color": "rgba(0, 0, 0, 0.00)"
            // "paneProperties.legendProperties.showSeriesTitle": false

        },
        time_frames: [
            {text: "5y", resolution: '1440', description: "All", title: "All", value: '60m', from: 0},
            {text: "1y", resolution: '360', description: "1 Year", value: '12m', from: 31536000},
            {text: "3m", resolution: '240', description: "3 Months", value: '3m', from: 7776000},
            {text: "1m", resolution: '120', description: "1 Month", value: '1m', from: 2592000},
            {text: "7d", resolution: '30', description: "7 Days", value: '7d', from: 604800},
            {text: "3d", resolution: '15', description: "3 Days", value: '3d', from: 259200},
            {text: "1d", resolution: "5", description: "1 Day", value: '1d', from: 86400},
        ],
    });

    widget.onChartReady(function () {
        // widget.chart().createStudy('MACD', false, false, [14,30, 'close', 9]);
        let price = undefined;
        widget.chart().crossHairMoved(function (x) {
            price = x.price
        });

        // widget.subscribe("mouse_up", function () {
        //
        // });

        // widget.chart().onIntervalChanged().unsubscribe(null, function (interval, obj) {
        //     if (obj.timeframe) {
        //         widget.chart().setResolution(interval.toString());
        //     }
        // });

        widget.chart().onIntervalChanged().subscribe(null, function (interval, obj) {
            if (obj.timeframe) {
                vueInstance.chartOptions.currentTimeFrameIndex = _.findIndex(widget._options.time_frames, function (timeFrame) {
                    return timeFrame.value === obj.timeframe;
                });


                vueInstance.chartOptions.start = obj.timeframe;
                vueInstance.chartOptions.interval = (interval * 60);

                if (vueInstance.chartOptions.currentTimeFrameIndex < vueInstance.chartOptions.minimumTimeFrameIndex) {
                    vueInstance.chartOptions.minimumTimeFrameIndex = vueInstance.chartOptions.currentTimeFrameIndex;
                    $('#tv_chart_container').css('visibility', 'hidden')
                    vueInstance.initChart(true);
                }
            } else {
                vueInstance.chartOptions.interval = (interval * 60);
                vueInstance.initChart(false);
            }
        });

        if (initialData.length > 0) {
            let lastDate = initialData[initialData.length - 1].date;
            let fromDate = parseInt(lastDate - vueInstance.chartOptions.fromDate);

            if (vueInstance.chartOptions.fromDate === 0 || fromDate <= initialData[0].date) {
                fromDate = initialData[0].date;
            }

            widget.chart().setVisibleRange({from: fromDate, to: lastDate});
        }
        $('#tv_chart_container').css('visibility', 'visible');
    });


    initialDataFeed.registerGetBarsHandler(function (symbolInfo, resolution, rangeStartDate, rangeEndDate, onDataCallback, onErrorCallback) {

        setTimeout(function () { // <<<--- DO AJAX REQUEST HERE
            let data = {};
            let dataLength = initialData.length;
            let date = [];
            let open = [];
            let high = [];
            let low = [];
            let close = [];
            let volume = [];

            for (let i = 0; i < dataLength; i++) {
                let intervalStart = parseInt(initialData[i]['date'] / vueInstance.chartOptions.interval) * vueInstance.chartOptions.interval;
                let dateLength = date.length;
                if (dateLength > 0 && date[dateLength - 1] === intervalStart) {
                    if (initialData[i]['high'] > high[dateLength - 1]) {
                        high[dateLength - 1] = initialData[i]['high'];
                    }

                    if (low[dateLength - 1] > initialData[i]['date']) {
                        low[dateLength - 1] = initialData[i]['low'];
                    }

                    close[dateLength - 1] = initialData[i]['close'];
                    volume[dateLength - 1] = bcadd(volume[dateLength - 1], initialData[i]['volume'], 8)
                } else {
                    date[dateLength] = initialData[i]['date'];
                    open[dateLength] = initialData[i]['open'];
                    high[dateLength] = initialData[i]['high'];
                    low[dateLength] = initialData[i]['low'];
                    close[dateLength] = initialData[i]['close'];
                    volume[dateLength] = initialData[i]['volume'];
                }

            }

            data = {
                t: date,
                o: open,
                h: high,
                l: low,
                c: close,
                v: volume
            };

            if (rangeEndDate < data.t[0]) {
                data = {s: 'no_data'};
            }

            let nodata = data.s === 'no_data';
            let bars = [];
            let barsCount = nodata ? 0 : data.t.length;
            let volumePresent = typeof data.v != 'undefined';
            let ohlPresent = typeof data.o != 'undefined';

            for (let i = 0; i < barsCount; ++i) {
                let barValue = {
                    time: data.t[i] * 1000,
                    close: data.c[i]
                };

                if (ohlPresent) {
                    barValue.open = data.o[i];
                    barValue.high = data.h[i];
                    barValue.low = data.l[i];
                } else {
                    barValue.open = barValue.high = barValue.low = barValue.close;
                }

                if (volumePresent) {
                    barValue.volume = data.v[i];
                }

                bars.push(barValue);
            }

            onDataCallback(bars, {noData: nodata, nextTime: data.nb || data.nextTime});

        }, 0);
    });
}
