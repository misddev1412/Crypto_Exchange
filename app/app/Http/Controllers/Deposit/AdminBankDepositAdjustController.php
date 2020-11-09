<?php

namespace App\Http\Controllers\Deposit;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deposit\AmountAdjustmentRequest;
use App\Models\Deposit\WalletDeposit;
use Exception;
use Illuminate\Http\JsonResponse;

class AdminBankDepositAdjustController extends Controller
{
    public function __invoke(AmountAdjustmentRequest $request, WalletDeposit $deposit): JsonResponse
    {
        $message = __('Something went wrong. Please try again.');
        if ($deposit->status !== STATUS_REVIEWING) {
            return response()->json([
                RESPONSE_MESSAGE_KEY => $message
            ], 400);
        }

        $attributes = $request->validated();

        try {
            $deposit->update($attributes);
            $message = __('Successfully deposit amount has been updated.');
        } catch (Exception $exception) {
            return response()->json([RESPONSE_STATUS_KEY => RESPONSE_TYPE_ERROR, RESPONSE_MESSAGE_KEY => $message]);
        }
        return response()->json([RESPONSE_STATUS_KEY => RESPONSE_TYPE_SUCCESS, RESPONSE_MESSAGE_KEY => $message]);
    }
}
