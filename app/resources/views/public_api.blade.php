@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', 'Test')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <h2 class="mb-4">Public API</h2>

                <p>Trademen provides HTTP APIs for interacting with the exchange only for public market data.</p>

                <ul class="list-group mb-3 pl-0">
                    <li class="list-group-item lf-toggle-bg-card">
                        <a href="#returnTicker" class="text-info">- returnTicker</a>
                    </li>
                    <li class="list-group-item lf-toggle-bg-card">
                        <a href="#returnOrderBook" class="text-info">- returnOrderBook</a>
                    </li>
                    <li class="list-group-item lf-toggle-bg-card">
                        <a href="#returnTradeHistory" class="text-info">- returnTradeHistory</a>
                    </li>
                    <li class="list-group-item lf-toggle-bg-card">
                        <a href="#returnChartData" class="text-info">- returnChartData</a>
                    </li>
                </ul>

                <p>The HTTP API allows read access to public market data through the public endpoint -</p>
                <p>Public HTTP Endpoint: <a class="text-info" href="javascript:">https://yourdomain.com/api/public</a>
                </p>

                <hr>
                <!-- return ticker-->
                <div id="returnTicker">
                    <h4 class="py-3">returnTicker</h4>
                    <p>Retrieves summary information for each currency/coin pair listed on the exchange.</p>
                    <p>
                        Ticker Endpoint:
                        <a class="text-info"
                           href="javascript:">https://yourdomain.com/api/public?command=returnTicker</a>
                    </p>

                    <table
                        class="table table-borderless table-striped lf-toggle-bg-card lf-toggle-border-card lf-toggle-border-color">
                        <tbody>
                        <tr>
                            <td class="w-25"><strong>Field</strong></td>
                            <td class="strong"><strong>Description</strong></td>
                        </tr>
                        <tr>
                            <td>last</td>
                            <td>Execution price for the most recent trade for this pair.</td>
                        </tr>
                        <tr>
                            <td>change</td>
                            <td>Price change percentage.</td>
                        </tr>
                        <tr>
                            <td>high24hr</td>
                            <td>The highest execution price for this pair within thec last 24 hours.</td>
                        </tr>
                        <tr>
                            <td>low24hr</td>
                            <td>The lowest execution price for this pair within the last 24 hours.</td>
                        </tr>
                        <tr>
                            <td>baseVolume</td>
                            <td>Base units traded in the last 24 hours.</td>
                        </tr>
                        <tr>
                            <td>tradeVolume</td>
                            <td>trade units traded in the last 24 hours.</td>
                        </tr>
                        </tbody>
                    </table>

                    <h5>Example:</h5>

                    <div class="card my-2 mb-3 lf-toggle-bg-card">
                        <div class="card-body">
<pre class="text-green">
    {
        "BTC_USD": {
            "last": "8180.000000000",
            "low24hr": "8183.00000000",
            "high24hr": "10369.00000000",
            "change": "5.99",
            "tradeVolume": "614.24470018",
            "baseVolume": "5694762.62500284"
        },
        "DOGE_BTC": {
            "last": "0.000000200",
            "low24hr": "0.000000190",
            "high24hr": "0.000000210",
            "change": "10.58",
            "tradeVolume": "1614.24470018",
            "baseVolume": "4694762.62500284"
        }
    }
</pre>
                        </div>
                    </div>

                    <p>Retrieving summary information for a specified currency/coin pair listed on the exchange - </p>

                    <table
                        class="table table-borderless table-striped lf-toggle-bg-card lf-toggle-border-card lf-toggle-border-color">
                        <tbody>
                        <tr>
                            <td class="w-25"><strong>Request Parameter</strong></td>
                            <td class="strong"><strong>Description</strong></td>
                        </tr>
                        <tr>
                            <td>tradePair</td>
                            <td>A pair like BTC_USD</td>
                        </tr>
                        </tbody>
                    </table>

                    <p>
                        Ticker Endpoint:
                        <a class="text-info" href="javascript:">
                            https://yourdomain.com/api/public?command=returnTicker&tradePair=BTC_USD
                        </a>
                    </p>

                    <h5>Example:</h5>

                    <div class="card my-2 mb-3 lf-toggle-bg-card">
                        <div class="card-body">
<pre class="text-green">
    {
        "last": "8180.000000000",
        "low24hr": "8183.00000000",
        "high24hr": "10369.00000000",
        "change": "5.99",
        "tradeVolume": "614.24470018",
        "baseVolume": "5694762.62500284"
    }
</pre>
                        </div>
                    </div>

                <hr>
                <!-- return order book-->
                <div id="returnOrderBook">
                    <h4 class="py-3">returnOrderBook</h4>
                    <p>Retrieves the latest 50 order book of each order type information for a specified currency/coin pair listed on the
                        exchange</p>
                    <p>
                        Order book Endpoint: <a class="text-info" href="javascript:">https://yourdomain.com/public?command=returnOrderBook&tradePair=BTC_USD</a>
                    </p>

                    <h5>Input Fields:</h5>

                    <table
                        class="table table-borderless table-striped lf-toggle-bg-card lf-toggle-border-card lf-toggle-border-color">
                        <thead>
                        <tr>
                            <th class="w-25">Request Parameter</th>
                            <th>Description</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>tradePair</td>
                            <td>A pair like BTC_ETH</td>
                        </tr>
                        </tbody>
                    </table>

                    <h5>Out Fields:</h5>

                    <table
                        class="table table-borderless table-striped lf-toggle-bg-card lf-toggle-border-card lf-toggle-border-color">
                        <tbody>
                        <tr>
                            <td class="w-25"><strong>Field</strong></td>
                            <td class="strong"><strong>Description</strong></td>
                        </tr>

                        <tr>
                            <td>asks</td>
                            <td>An array of price aggregated offers in the book ordered from low to high price.</td>
                        </tr>
                        <tr>
                            <td>bids</td>
                            <td>An array of price aggregated bids in the book ordered from high to low price.</td>
                        </tr>
                        </tbody>
                    </table>

                    <h5>Example:</h5>

                    <div class="card my-2 mb-3 lf-toggle-bg-card">
                        <div class="card-body">
<pre class="text-green">
    {
      "asks": [
        {
          "price": "0.09000000",
          "amount": "500.00000000",
          "total": "45.00000000"
        },
        {
          "price": "0.11000000",
          "amount": "700.00000000",
          "total": "77.00000000"
        }
        ...
      ],
      "bids": [
        {
          "price": "0.10000000",
          "amount": "700.00000000",
          "total": "70.00000000"
        },
        {
          "price": "0.09000000",
          "amount": "500.00000000",
          "total": "45.00000000"
        }
        ...
      ]
    }
</pre>

                            </div>
                        </div>
                    </div>


                </div>

                <hr>
                <!-- return trade history-->
                <div id="returnTradeHistory">
                    <h4 class="py-3">returnTradeHistory</h4>
                    <p>
                        Returns the past 100 trades for a given market,
                        You may set a range specified in UNIX timestamps by the “start” and “end” GET parameters.
                    </p>
                    <p>
                        Trade History Endpoint: <a class="text-info" href="javascript:">
                            https://yourdomain.com/public?command=returnTradeHistory&tradePair=BTC_USD
                        </a>
                    </p>

                    <p>
                        Trade History Endpoint: <a class="text-info" href="javascript:">
                            https://yourdomain.com/public?command=returnTradeHistory&tradePair=BTC_USD&start=1593419220&end=1593423660
                        </a>
                    </p>

                    <h5>Input Fields:</h5>

                    <table
                        class="table table-borderless table-striped lf-toggle-bg-card lf-toggle-border-card lf-toggle-border-color">
                        <tbody>
                        <tr>
                            <td class="w-25"><strong>Request Parameter</strong></td>
                            <td class="strong"><strong>Description</strong></td>
                        </tr>
                        <tr>
                            <td>tradePair</td>
                            <td>A pair like BTC_ETH</td>
                        </tr>
                        <tr>
                            <td>start (optional)</td>
                            <td>The start of the window in seconds since the unix epoch.</td>
                        </tr>
                        <tr>
                            <td>end (optional)</td>
                            <td>The end of the window in seconds since the unix epoch.</td>
                        </tr>
                        </tbody>
                    </table>

                    <h5>Out Fields:</h5>

                    <table
                        class="table table-borderless table-striped lf-toggle-bg-card lf-toggle-border-card lf-toggle-border-color">
                        <tbody>
                        <tr>
                            <td class="w-25"><strong>Field</strong></td>
                            <td class="strong"><strong>Description</strong></td>
                        </tr>

                        <tr>
                            <td>date</td>
                            <td>The UTC date and time of the trade execution.</td>
                        </tr>
                        <tr>
                            <td>type</td>
                            <td>Designates this trade as a buy or a sell from the side of the taker.</td>
                        </tr>
                        <tr>
                            <td>price</td>
                            <td>The price in base currency for this asset.</td>
                        </tr>
                        <tr>
                            <td>amount</td>
                            <td>The number of units transacted in this trade.</td>
                        </tr>
                        <tr>
                            <td>total</td>
                            <td>The total price in base units for this trade.</td>
                        </tr>
                        </tbody>
                    </table>

                    <h5>Example:</h5>

                    <div class="card my-2 mb-3 lf-toggle-bg-card">
                        <div class="card-body">
<pre class="text-green">
    [
      {
        "price": "9860.86031280",
        "amount": "0.85441089",
        "total": "8425.22643602",
        "type": "buy",
        "date": "2020-06-29 10:03:00"
      },
      {
        "price": "9862.25325181",
        "amount": "0.15549235",
        "total": "1533.50493441",
        "type": "sell",
        "date": "2020-06-29 10:02:00"
      },
      ...
    ]
</pre>
                        </div>
                    </div>
                </div>

                <hr>
                <!-- return chart data-->
                <div id="returnChartData">
                    <h4 class="py-3">returnChartData</h4>
                    <p class="has-line-data" data-line-start="91" data-line-end="92">
                        Returns candlestick chart data. Required GET parameters
                        are <code>tradePair</code>, (candlestick period in seconds; valid values
                        are <code>300</code>,
                        <code>900</code>, <code>1800</code>, <code>7200</code>, <code>14400</code>, and
                        <code>86400</code>),
                        <code>start</code>, and <code>end</code>. <code>Start</code> and <code>end</code> are given in
                        UNIX timestamp format
                        and used to specify the date range for the data returned. Fields include:
                    </p>

                    <p>
                        Chart Data Endpoint: <a class="text-info" href="javascript:">
                            https://yourdomain.com/public?command=returnChartData&tradePair=BTC_USD&interval=900&start=1546300800&end=1546646400
                        </a>
                    </p>

                    <h5>Input Fields:</h5>

                    <table
                        class="table table-borderless table-striped lf-toggle-bg-card lf-toggle-border-card lf-toggle-border-color">
                        <tbody>
                        <tr>
                            <td class="w-25"><strong>Request Parameter</strong></td>
                            <td class="strong"><strong>Description</strong></td>
                        </tr>

                        <tr>
                            <td>tradePair</td>
                            <td>The currency pair of the market being requested.</td>
                        </tr>
                        <tr>
                            <td>interval</td>
                            <td>Candlestick period/interval in seconds. Valid values are 300, 900, 1800, 7200, 14400, and
                                86400.
                            </td>
                        </tr>
                        <tr>
                            <td>start</td>
                            <td>The start of the window in seconds since the unix epoch.</td>
                        </tr>
                        <tr>
                            <td>end</td>
                            <td>The end of the window in seconds since the unix epoch.</td>
                        </tr>
                        </tbody>
                    </table>

                    <h5>Out Fields:</h5>

                    <table
                        class="table table-borderless table-striped lf-toggle-bg-card lf-toggle-border-card lf-toggle-border-color">
                        <tbody>
                        <tr>
                            <td class="w-25"><strong>Field</strong></td>
                            <td class="strong"><strong>Description</strong></td>
                        </tr>
                        <tr>
                            <td>date</td>
                            <td>The UTC date for this candle in miliseconds since the Unix epoch.</td>
                        </tr>
                        <tr>
                            <td>high</td>
                            <td>The highest price for this asset within this candle.</td>
                        </tr>
                        <tr>
                            <td>low</td>
                            <td>The lowest price for this asset within this candle.</td>
                        </tr>
                        <tr>
                            <td>open</td>
                            <td>The price for this asset at the start of the candle.</td>
                        </tr>
                        <tr>
                            <td>close</td>
                            <td>The price for this asset at the end of the candle.</td>
                        </tr>
                        <tr>
                            <td>volume</td>
                            <td>The total amount of this asset transacted within this candle.</td>
                        </tr>
                        </tbody>
                    </table>

                    <h5>Example:</h5>

                    <div class="card my-2 mb-3 lf-toggle-bg-card">
                        <div class="card-body">
<pre class="text-green">
    [
      {
        "date": 1593396900,
        "low": "10112.27439575",
        "high": "10115.44996344",
        "volume": "1.54063724",
        "open": "10115.44996344",
        "close": "10112.27439575"
      },
      {
        "date": 1593397800,
        "low": "10061.35948383",
        "high": "10112.27439575",
        "volume": "6.88096652",
        "open": "10112.27439575",
        "close": "10061.35948383"
      },
      ...
    ]
</pre>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('style')
    @include('layouts.includes.list-css')
@endsection

@section('script')
    @include('layouts.includes.list-js')
@endsection
