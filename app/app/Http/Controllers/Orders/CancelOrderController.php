<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Jobs\Order\CancelOrderJob;
use App\Models\Order\Order;
use Exception;
use Illuminate\Support\Facades\Auth;

class CancelOrderController extends Controller
{
    public function destroy(Order $order)
    {
        try {
            if (Auth::id() != $order->user_id) {
                throw new Exception(__('You are not authorize to do this action.'));
            }

            if ( !in_array($order->status, [STATUS_PENDING, STATUS_INACTIVE]) ) {
                throw new Exception(__('This order cannot be deleted.'));
            }

            CancelOrderJob::dispatch($order);
        } catch (Exception $exception) {
            if (request()->ajax()) {
                return response()
                    ->json([RESPONSE_MESSAGE_KEY => $exception->getMessage()], 400);
            } else {
                return redirect()
                    ->back()
                    ->with(RESPONSE_TYPE_ERROR, $exception->getMessage());
            }
        }

        $successMessage = __('The order cancellation request has been placed successfully.');

        if (request()->ajax()) {
            return response()
                ->json([RESPONSE_MESSAGE_KEY => $successMessage], 200);
        } else {
            return redirect()
                ->back()
                ->with(RESPONSE_TYPE_SUCCESS, $successMessage);
        }
    }
}
