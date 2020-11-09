<?php

namespace App\Http\Controllers\CoinPair;

use App\Http\Controllers\Controller;
use App\Models\Coin\CoinPair;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class MakeDefaultAdminCoinPairController extends Controller
{
    public function __invoke(CoinPair $coinPair): RedirectResponse
    {
        if ($coinPair->is_default == ACTIVE) {
            return redirect()->back()->with(RESPONSE_TYPE_WARNING, __('This coin is already assigned as default.'));
        }

        DB::beginTransaction();
        try {

            $previousDefaultCoinPair = CoinPair::where(['is_default' => ACTIVE])->first();
            if(!empty($previousDefaultCoinPair)){
                $previousDefaultCoinPair->update(['is_default' => INACTIVE]);
            }

            $updateDefaultCoinPair = $coinPair->update(['is_default' => ACTIVE]);

            if (!$updateDefaultCoinPair) {
                throw new Exception(__('Failed to make default.'));
            }

        } catch (Exception $exception) {
            DB::rollBack();
            logs()->error("Make default coin pair: " . $exception->getMessage());
            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to make default.'));
        }

        DB::commit();

        return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __('The coin pair has been made default successfully.'));
    }
}
