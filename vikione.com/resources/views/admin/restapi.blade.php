@extends('layouts.admin')
@section('title', 'Application API')
@section('content')
<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="main-content col-lg-12">
                <div class="content-area card">
                    <div class="card-innr">
                        <div class="card-head">
                            <h4 class="card-title">Application Access API</h4>
                        </div>
                        <div class="card-text wide-max-md">
                            <p>Using Application API to access your application internal data from external website such as from your landing page or main website. Application provides some specific internal live data in JSON mode that helps you to connect and populate to your current landing page.</p>
                            
                        </div>
                        <div class="sap sap-gap-sm"></div>
                        <div class="row align-items-center guttar-vr-15px">
                            <div class="col-sm-7">
                                <p><span class="ucap text-light">Access API Key:</span> <br><code>{{ get_setting('site_api_key') }}</code></p>
                            </div>
                            <div class="col-sm-5 text-sm-right">
                                <form action="{{ route('admin.ajax.settings.update') }}" method="POST" onsubmit="return confirm('Are you sure do you want to regenarate key? Please note that previous access url or endpoint not work any more.')">
                                    @csrf
                                    <input type="hidden" name="type" value="api_settings">
                                    <button title="Regenerate Key" type="submit" class="btn btn-auto btn-primary"><i class="ti ti-reload mr-1"></i> Regenerate</button>
                                    <div class="gaps-0-5x d-sm-none"></div>
                                </form>
                            </div>
                        </div>
                        <div class="sap sap-gap-sm"></div>
                        <h3 class="card-title">Stage Information</h3>
                        <p>Retrieving current active stage information. This endpoint is public and it allow to get stage total token, total sales, start date, end date, stage price etc.</p>

                        @if(is_demo_user()) 
                        <h4><a href="http://bit.ly/2XenYde" target="_blank">Check out an example of external landing page that connected with this application.</a></h4>
                        <div class="alert alert-dim alert-danger">
                        <p><em><strong>Caution:</strong> The example of integration code is not part of product, If you need help to integrate into your landing page, please contact us.</em></p>
                        <p><em><strong>Note:</strong> The demo based on <a href="https://themeforest.net/item/ico-crypto-bitcoin-cryptocurrency-landing-page-html-template/21405614?rel=softnio">ICO Crypto Landing Page Template</a>, If you use this for your landing page, then we'll help you to integrate it free of charge.</em></p>
                        </div>
                        <div class="sap sap-gap"></div>
                        @endif

                        <h5>Minimal Stage Data <span class="badge badge-auto badge-xs badge-success">GET</span> <span class="badge badge-auto badge-dim badge-xs badge-success">/stage</span></h5>
                        <code class="code-block">
                        {{ api_route('stage') }}
                        </code>

                        <div class="gaps-2x"></div>
                        <h5>Full Stage Data <span class="badge badge-auto badge-xs badge-success">GET</span> <span class="badge badge-auto badge-dim badge-xs badge-success">/stage/full</span></h5>
                        <code class="code-block">
                        {{ api_route('stage.full') }}
                        </code>

                        <div class="gaps-2x"></div>
                        <h5>Current Prices <span class="badge badge-auto badge-xs badge-success">GET</span> <span class="badge badge-auto badge-dim badge-xs badge-success">/price</span></h5>
                        <code class="code-block">
                        {{ api_route('price') }}
                        </code>

                        <div class="gaps-2x"></div>
                        <h5>Active Bonus <span class="badge badge-auto badge-xs badge-success">GET</span> <span class="badge badge-auto badge-dim badge-xs badge-success">/bonus</span></h5>
                        <code class="code-block">
                        {{ api_route('bonus') }}
                        </code>

                        <div class="gaps-2x"></div>
                        <p><strong>Result Format Example</strong> <span class="badge badge-auto badge-dim badge-xs badge-success">200 OK</span></p>
<pre class="code-block">
{
    "success": true,
    "response": {
        "ico": "running",
        "total": 850000,
        "total_token": "850,000 TLE",
        "total_amount": "340,000 EUR",
        "sold": 24238.5,
        "sold_token": "24,239 TLE",
        "sold_amount": "9,695.4 EUR",
        "progress": 2.9,
        "price": 0.4,
        "bonus": {
            "base": 0,
            "amount": {
                "500": 10,
                "1500": 15
            }
        },
        "start": "2019-04-12 23:57:00",
        "end": "2019-05-31 00:45:00",
        "min": 100,
        "max": 10000,
        "soft": {
            "cap": 350000,
            "percent": 41.2
        },
        "hard": {
            "cap": 0,
            "percent": 0
        }
    }
}
</pre>

                    </div>{{-- .card-innr --}}
                </div>{{-- .card --}}

            </div>{{-- .col --}}
        </div>{{-- .container --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection