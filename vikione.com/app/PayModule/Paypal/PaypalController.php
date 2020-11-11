<?php

namespace App\PayModule\Paypal;

use Illuminate\Http\Request;
use App\PayModule\Paypal\PaypalPay;
use App\Http\Controllers\Controller;

class PaypalController extends Controller
{
    private $instance;

    public function __construct()
    {
        $this->instance = new PaypalPay();
    }
    public function success(Request $request)
    {
        if(method_exists($this->instance, 'paypal_success')){
            return $this->instance->paypal_success($request);
        }
    }

    public function cancel(Request $request, $name='Order has been canceled due to payment!')
    {
        if(method_exists($this->instance, 'payment_cancel')){
            return $this->instance->payment_cancel($request, $name);
        }
    }
}
