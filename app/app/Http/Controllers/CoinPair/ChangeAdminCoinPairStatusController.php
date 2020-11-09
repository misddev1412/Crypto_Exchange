<?php

    namespace App\Http\Controllers\CoinPair;

    use App\Http\Controllers\Controller;
    use App\Models\Coin\CoinPair;
    use Illuminate\Http\RedirectResponse;

    class ChangeAdminCoinPairStatusController extends Controller
    {
        public function change(CoinPair $coinPair): RedirectResponse
        {
            if ($coinPair->coin->is_active == INACTIVE) {
                return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('The associated trade coin is inactive.'));
            }

            if ($coinPair->baseCoin->is_active == INACTIVE) {
                return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('The associated base coin is inactive.'));
            }

            if ($coinPair->toggleStatus()) {
                cookie()->forget('baseCoins');

                return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __('Successfully coin pair status changed. Please try again.'));
            }

            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to change status. Please try again.'));
        }
    }
