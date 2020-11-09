<?php

    namespace App\Http\Controllers\Coin;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Coin\CoinIconRequest;
    use App\Models\Coin\Coin;
    use App\Services\Coins\CoinService;
    use App\Services\Core\FileUploadService;
    use Illuminate\Contracts\View\View;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\RedirectResponse;

    class AdminCoinIconOptionController extends Controller
    {
        public function update(CoinIconRequest $request, Coin $coin): JsonResponse
        {
            $attributes = [];
            if ($request->hasFile('icon')) {
                $coinIcon = app(FileUploadService::class)
                    ->upload($request->icon, config('commonconfig.path_coin_icon'), '', '', $coin->symbol, 'public', 300, 300, false);

                if ($coinIcon) {
                    $attributes['icon'] = $coinIcon;
                } else {
                    return response()->json([RESPONSE_STATUS_KEY => RESPONSE_TYPE_ERROR, RESPONSE_MESSAGE_KEY => __('Failed to update coin icon.')]);
                }
            }

            if ($coin->update($attributes)) {
                return response()->json([RESPONSE_STATUS_KEY => RESPONSE_TYPE_SUCCESS, RESPONSE_MESSAGE_KEY => __('The coin icon has been updated successfully.'), 'icon' => get_coin_icon($attributes['icon'])]);
            }

            return response()->json([RESPONSE_STATUS_KEY => RESPONSE_TYPE_ERROR, RESPONSE_MESSAGE_KEY => __('Failed to update coin icon.')]);
        }
    }
