<?php

namespace Database\Seeders;

use App\Models\Page\Page;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = now();

        $pages = [
            [
                'slug' => 'about-us',
                'title' => 'About Us',
                'content' => '<h2>About Us</h2>
<p> </p>
<p>Founded in June of 2020, Trademen is a digital currency wallet and trading platform where users can trade and exchange crypto currencies like bitcoin,litecoin and many more.</p>
<p>Vivamus faucibus blandit neque, a lobortis purus congue sed. Mauris dapibus mi in felis consectetur blandit. In pellentesque, magna id eleifend scelerisque, odio augue interdum ex, id mollis purus enim ac risus. Fusce dui sem, faucibus quis ligula quis, dictum fermentum mauris. Morbi eu est dolor. Maecenas bibendum a urna ut ultricies. Vivamus vulputate et leo eu imperdiet. Proin nec dui mi. Etiam euismod felis eu laoreet mattis. Nullam tempus lobortis eros at rhoncus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi vitae mauris ac mauris aliquam venenatis.</p>
<p>In volutpat quam sit amet odio luctus aliquet. Vivamus ultricies ante lobortis metus maximus, at posuere ex blandit. Sed et lectus mollis, sagittis ex eget, malesuada lectus. Mauris placerat luctus tellus ac malesuada. In ipsum erat, egestas et dolor consectetur, pellentesque placerat nisi. Donec sodales ut lorem ac accumsan. Proin pulvinar tincidunt ex sed rhoncus. Aliquam pharetra erat at ante ultricies, in blandit justo iaculis. Aliquam tempor ac est ut sollicitudin.</p>',
                'meta_description' => 'about, about us, about us page,',
                'meta_keywords' => '["about","trademen","exchange","trading","crypto","currencies"]',
                'is_published' => ACTIVE,
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'slug' => 'api',
                'title' => 'API',
                'content' => '<h2 class="mb-4">Public API</h2>
<p>Trademen provides HTTP APIs for interacting with the exchange only for public market data.</p>
<ul class="list-group mb-3 pl-0">
<li class="list-group-item lf-toggle-bg-card"><a class="text-info" href="#returnTicker">- returnTicker</a></li>
<li class="list-group-item lf-toggle-bg-card"><a class="text-info" href="#returnOrderBook">- returnOrderBook</a></li>
<li class="list-group-item lf-toggle-bg-card"><a class="text-info" href="#returnTradeHistory">- returnTradeHistory</a></li>
<li class="list-group-item lf-toggle-bg-card"><a class="text-info" href="#returnChartData">- returnChartData</a></li>
</ul>
<p>The HTTP API allows read access to public market data through the public endpoint -</p>
<p>Public HTTP Endpoint: <a class="text-info">https://yourdomain.com/api/public</a></p>

<div id="returnTicker">
<h4 class="py-3">returnTicker</h4>
<p>Retrieves summary information for each currency/coin pair listed on the exchange.</p>
<p>Ticker Endpoint: <a class="text-info">https://yourdomain.com/api/public?command=returnTicker</a></p>
<table class="table table-borderless table-striped lf-toggle-bg-card lf-toggle-border-card lf-toggle-border-color">
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
<pre class="text-green">    {
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
<p>Retrieving summary information for a specified currency/coin pair listed on the exchange -</p>
<table class="table table-borderless table-striped lf-toggle-bg-card lf-toggle-border-card lf-toggle-border-color">
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
<p>Ticker Endpoint: <a class="text-info"> https://yourdomain.com/api/public?command=returnTicker&amp;tradePair=BTC_USD </a></p>
<h5>Example:</h5>
<div class="card my-2 mb-3 lf-toggle-bg-card">
<div class="card-body">
<pre class="text-green">    {
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

<div id="returnOrderBook">
<h4 class="py-3">returnOrderBook</h4>
<p>Retrieves the latest 50 order book of each order type information for a specified currency/coin pair listed on the exchange</p>
<p>Order book Endpoint: <a class="text-info">https://yourdomain.com/public?command=returnOrderBook&amp;tradePair=BTC_USD</a></p>
<h5>Input Fields:</h5>
<table class="table table-borderless table-striped lf-toggle-bg-card lf-toggle-border-card lf-toggle-border-color">

<tbody>
<tr>
<td>tradePair</td>
<td>A pair like BTC_ETH</td>
</tr>
</tbody>
</table>
<h5>Out Fields:</h5>
<table class="table table-borderless table-striped lf-toggle-bg-card lf-toggle-border-card lf-toggle-border-color">
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
<pre class="text-green">    {
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

<div id="returnTradeHistory">
<h4 class="py-3">returnTradeHistory</h4>
<p>Returns the past 100 trades for a given market, You may set a range specified in UNIX timestamps by the “start” and “end” GET parameters.</p>
<p>Trade History Endpoint: <a class="text-info"> https://yourdomain.com/public?command=returnTradeHistory&amp;tradePair=BTC_USD </a></p>
<p>Trade History Endpoint: <a class="text-info"> https://yourdomain.com/public?command=returnTradeHistory&amp;tradePair=BTC_USD&amp;start=1593419220&amp;end=1593423660 </a></p>
<h5>Input Fields:</h5>
<table class="table table-borderless table-striped lf-toggle-bg-card lf-toggle-border-card lf-toggle-border-color">
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
<table class="table table-borderless table-striped lf-toggle-bg-card lf-toggle-border-card lf-toggle-border-color">
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
<pre class="text-green">    [
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

<div id="returnChartData">
<h4 class="py-3">returnChartData</h4>
<p>Returns candlestick chart data. Required GET parameters are tradePair, (candlestick period in seconds; valid values are 300, 900, 1800, 7200, 14400, and 86400), start, and end. Start and end are given in UNIX timestamp format and used to specify the date range for the data returned. Fields include:</p>
<p>Chart Data Endpoint: <a class="text-info"> https://yourdomain.com/public?command=returnChartData&amp;tradePair=BTC_USD&amp;interval=900&amp;start=1546300800&amp;end=1546646400 </a></p>
<h5>Input Fields:</h5>
<table class="table table-borderless table-striped lf-toggle-bg-card lf-toggle-border-card lf-toggle-border-color">
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
<td>Candlestick period/interval in seconds. Valid values are 300, 900, 1800, 7200, 14400, and 86400.</td>
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
<table class="table table-borderless table-striped lf-toggle-bg-card lf-toggle-border-card lf-toggle-border-color">
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
<pre class="text-green">    [
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
</div>',
                'meta_description' => 'api, public api, tradmen',
                'meta_keywords' => '["API","public","http","public api","trademen"]',
                'is_published' => ACTIVE,
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'slug' => 'privacy-policy',
                'title' => 'Privacy Policy',
                'content' => '<h2>Privacy Policy</h2>
<p> </p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris a odio non mi tincidunt malesuada. Sed mattis, ex eget porttitor iaculis, purus neque ultricies arcu, sit amet viverra neque enim sed neque. Vivamus eget felis in tellus mattis interdum id ut odio. Sed id lorem laoreet, gravida velit a, tincidunt magna. Aliquam non dictum arcu. In dignissim sit amet lorem vel efficitur. Quisque vel mattis dui. Duis elit ligula, aliquam eu tempus rhoncus, aliquam et ante. Sed vitae augue maximus, auctor ex id, interdum purus. Nulla lacinia, sapien eget bibendum porta, leo ex fermentum magna, ac cursus nulla arcu eu dui. Aliquam arcu sapien, congue ac leo venenatis, pretium tempus nisl. Phasellus malesuada nulla a luctus iaculis. Nulla sed tempus nisl. Cras iaculis ultricies nulla, nec lobortis sem. Fusce finibus bibendum felis, id pulvinar eros vestibulum eget.</p>
<p>Cras euismod tellus sit amet risus eleifend elementum. Suspendisse eu orci lectus. In auctor fermentum nunc, eu auctor risus maximus in. Morbi fringilla odio sem, et vestibulum arcu aliquam ac. Nam quis felis massa. Nunc eu lobortis est. Duis ut venenatis lectus. Fusce eros purus, blandit eu augue non, vulputate placerat sapien. Ut eu dui lorem.</p>
<p>Cras sed tempor sem. Pellentesque porta risus non erat eleifend, egestas rutrum felis vulputate. Sed commodo urna sed enim pretium varius. Nunc sed hendrerit odio. Donec eu tortor justo. Sed faucibus velit et diam auctor, nec placerat massa ultrices. Proin porta eu justo et iaculis. Proin ut elit a ligula lobortis varius feugiat in felis. Proin molestie massa vitae volutpat aliquam. Morbi arcu nisi, tempus sit amet laoreet eget, elementum sed neque. Vivamus condimentum orci vitae ex lacinia, id rutrum lacus aliquam. Sed nec odio eu diam ullamcorper dapibus quis vel nunc. Duis sed dictum mauris, ut ultrices lorem. Suspendisse faucibus est turpis, vitae volutpat sem tempus non. Pellentesque odio nisi, efficitur vel urna vel, ultrices fermentum velit. Fusce suscipit nisl sed fermentum fringilla.</p>
<p>Quisque sagittis mollis venenatis. Integer sed vestibulum neque, eget vulputate nulla. Quisque magna leo, cursus quis augue nec, gravida ornare ipsum. Nulla dignissim ante lorem, eu dignissim lectus tempor eget. Cras feugiat facilisis dolor, ac sagittis justo suscipit rutrum. Cras molestie varius ante, sit amet porta dui ornare eget. Aenean auctor auctor quam, sed interdum elit consequat ut. Praesent faucibus, libero non vulputate placerat, est tortor dignissim nulla, a sollicitudin elit ipsum at justo. Maecenas fermentum nibh eget leo aliquet scelerisque. Suspendisse potenti. Sed nec orci porta, maximus nulla eget, accumsan tellus. Nam viverra mauris felis, ac accumsan sapien tempus molestie.</p>
<p>Praesent placerat, libero sit amet laoreet sollicitudin, augue leo cursus mauris, ut ultrices tortor lacus in nunc. Ut suscipit dolor vitae scelerisque cursus. Duis vestibulum nec tellus in accumsan. Proin laoreet augue vel laoreet convallis. Proin commodo, nisi eget efficitur tincidunt, neque sapien commodo dolor, vel euismod urna mauris quis elit. Etiam varius ligula scelerisque, suscipit velit vel, maximus risus. Nullam ultrices ante ut massa imperdiet, sit amet efficitur libero convallis. Nulla non nunc ex. Pellentesque pellentesque egestas leo, at tempus elit cursus non. Integer convallis accumsan semper. Nulla ornare ex sed tortor iaculis, vitae aliquet massa vestibulum. Etiam finibus ipsum diam.</p>',
                'meta_description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s,',
                'meta_keywords' => '["privacy","policy","privacy policy"]',
                'is_published' => ACTIVE,
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'slug' => 'referral-programs',
                'title' => 'Referral Programs',
                'content' => '<h2>Referral Programs</h2>
<p> </p>
<p>Trademen follows Unilevel referral progamr. Unilevel compensation plan is a single level plan with unlimited members on the front-line. This plan ensures maximum benefit for the collective effort of a distributor.</p>
<p> </p>
<div class="row">
<div class="col-md-3 col-sm-6">
<h3>Get your referral link</h3>
<p>All Trademen customers can access their referral link from their profile page. Login to your Trademen account to find your referral link.</p>
</div>
<div class="col-md-3 col-sm-6">
<h3>Invite your friends</h3>
<p>Share your referral link with your friends, family, and on your social networks. Every person who signs up using your link will be another person you can earn from.</p>
</div>
<div class="col-md-3 col-sm-6">
<h3>Start your earning</h3>
<p>When your referrals trade on Trademen you’ll earn 10% of the trading fees they pay.</p>
</div>
<div class="col-md-3 col-sm-6">
<h3>Expand your network</h3>
<p>The more you share, the more you earn. Continue sharing your referral link with your network to keep the earnings coming.</p>
</div>
</div>',
                'meta_description' => 'Trademen, Buy, sell, and trade Bitcoin (BTC), Ethereum (ETH), TRON (TRX), Tether (USDT), and the best altcoins on the market with the legendary crypto exchange.',
                'meta_keywords' => '["Referral","Referral program"]',
                'is_published' => ACTIVE,
                'created_at' => $date,
                'updated_at' => $date,
            ],

            [
                'slug' => 'terms-and-conditions',
                'title' => 'Terms and Conditions',
                'content' => '<h2>Terms and Conditions</h2>
<p> </p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris a odio non mi tincidunt malesuada. Sed mattis, ex eget porttitor iaculis, purus neque ultricies arcu, sit amet viverra neque enim sed neque. Vivamus eget felis in tellus mattis interdum id ut odio. Sed id lorem laoreet, gravida velit a, tincidunt magna. Aliquam non dictum arcu. In dignissim sit amet lorem vel efficitur. Quisque vel mattis dui. Duis elit ligula, aliquam eu tempus rhoncus, aliquam et ante. Sed vitae augue maximus, auctor ex id, interdum purus. Nulla lacinia, sapien eget bibendum porta, leo ex fermentum magna, ac cursus nulla arcu eu dui. Aliquam arcu sapien, congue ac leo venenatis, pretium tempus nisl. Phasellus malesuada nulla a luctus iaculis. Nulla sed tempus nisl. Cras iaculis ultricies nulla, nec lobortis sem. Fusce finibus bibendum felis, id pulvinar eros vestibulum eget.</p>
<p>Cras euismod tellus sit amet risus eleifend elementum. Suspendisse eu orci lectus. In auctor fermentum nunc, eu auctor risus maximus in. Morbi fringilla odio sem, et vestibulum arcu aliquam ac. Nam quis felis massa. Nunc eu lobortis est. Duis ut venenatis lectus. Fusce eros purus, blandit eu augue non, vulputate placerat sapien. Ut eu dui lorem.</p>
<p>Cras sed tempor sem. Pellentesque porta risus non erat eleifend, egestas rutrum felis vulputate. Sed commodo urna sed enim pretium varius. Nunc sed hendrerit odio. Donec eu tortor justo. Sed faucibus velit et diam auctor, nec placerat massa ultrices. Proin porta eu justo et iaculis. Proin ut elit a ligula lobortis varius feugiat in felis. Proin molestie massa vitae volutpat aliquam. Morbi arcu nisi, tempus sit amet laoreet eget, elementum sed neque. Vivamus condimentum orci vitae ex lacinia, id rutrum lacus aliquam. Sed nec odio eu diam ullamcorper dapibus quis vel nunc. Duis sed dictum mauris, ut ultrices lorem. Suspendisse faucibus est turpis, vitae volutpat sem tempus non. Pellentesque odio nisi, efficitur vel urna vel, ultrices fermentum velit. Fusce suscipit nisl sed fermentum fringilla.</p>
<p>Quisque sagittis mollis venenatis. Integer sed vestibulum neque, eget vulputate nulla. Quisque magna leo, cursus quis augue nec, gravida ornare ipsum. Nulla dignissim ante lorem, eu dignissim lectus tempor eget. Cras feugiat facilisis dolor, ac sagittis justo suscipit rutrum. Cras molestie varius ante, sit amet porta dui ornare eget. Aenean auctor auctor quam, sed interdum elit consequat ut. Praesent faucibus, libero non vulputate placerat, est tortor dignissim nulla, a sollicitudin elit ipsum at justo. Maecenas fermentum nibh eget leo aliquet scelerisque. Suspendisse potenti. Sed nec orci porta, maximus nulla eget, accumsan tellus. Nam viverra mauris felis, ac accumsan sapien tempus molestie.</p>
<p>Praesent placerat, libero sit amet laoreet sollicitudin, augue leo cursus mauris, ut ultrices tortor lacus in nunc. Ut suscipit dolor vitae scelerisque cursus. Duis vestibulum nec tellus in accumsan. Proin laoreet augue vel laoreet convallis. Proin commodo, nisi eget efficitur tincidunt, neque sapien commodo dolor, vel euismod urna mauris quis elit. Etiam varius ligula scelerisque, suscipit velit vel, maximus risus. Nullam ultrices ante ut massa imperdiet, sit amet efficitur libero convallis. Nulla non nunc ex. Pellentesque pellentesque egestas leo, at tempus elit cursus non. Integer convallis accumsan semper. Nulla ornare ex sed tortor iaculis, vitae aliquet massa vestibulum. Etiam finibus ipsum diam.</p>',
                'meta_description' => 'Trademen, Buy, sell, and trade Bitcoin (BTC), Ethereum (ETH), TRON (TRX), Tether (USDT), and the best altcoins on the market with the legendary crypto exchange.',
                'meta_keywords' => '["terms","conditions","user aggrements"]',
                'is_published' => ACTIVE,
                'created_at' => $date,
                'updated_at' => $date,
            ],
        ];

        Page::insert($pages);
    }
}
