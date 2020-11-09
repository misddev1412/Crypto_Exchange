<?php

namespace App\Http\Controllers\Api\Webhook;

use App\Http\Controllers\Controller;
use App\Jobs\Webhook\ValidateBitcoinIpnJob;
use Illuminate\Http\Request;

class BitcoinIpnController extends Controller
{
    public function __invoke(Request $request, $currency)
    {
        if ($request->has('txn_id') && $request->get('txn_id')) {
            $ipnData = $request->only('txn_id');
            ValidateBitcoinIpnJob::dispatch($currency, $ipnData);
        }

        return [];
    }
}
