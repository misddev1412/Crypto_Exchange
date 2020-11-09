<?php

namespace App\Http\Controllers\CoinPair;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coin\CoinPairRequest;
use App\Models\Coin\Coin;
use App\Models\Coin\CoinPair;
use App\Services\Coins\CoinPairService;
use App\Services\Coins\CoinService;
use App\Services\Core\DataTableService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminCoinPairController extends Controller
{
    protected $service;

    public function index(): View
    {
        $data['title'] = __('Coin Pair');

        $searchFields = [
            ['name', __('Name')],
            ['coin', __('Coin')],
            ['base_coin', __('Base Coin')],
        ];

        $orderFields = [
            ['name', __('Name')],
            ['coin', __('Coin')],
            ['base_coin', __('Base Coin')],
            ['created_at', __('Date')],
        ];

        $queryBuilder = CoinPair::query();

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->create($queryBuilder);
        return view('coin_pairs.admin.index', $data);
    }

    public function create(): View
    {
        $data['coins'] = Coin::active()->pluck('symbol', 'symbol')->toArray();
        $data['title'] = __('Create Coin Pair');
        return view('coin_pairs.admin.create', $data);
    }

    public function store(CoinPairRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $attributes = $request->only('trade_coin', 'base_coin', 'is_active', 'last_price', 'is_default');

            if ($request->is_default == ACTIVE) {
                $defaultCoinPair = CoinPair::where(['is_default' => ACTIVE])->first();
                if (!empty($defaultCoinPair)) {
                    $defaultCoinPair->update(['is_default' => INACTIVE]);
                }
            }

            $coinPair = CoinPair::create($attributes);
            DB::commit();

            cache()->forget('baseCoins');

            return redirect()
                ->route('coin-pairs.edit', $coinPair->name)
                ->with(RESPONSE_TYPE_SUCCESS, __('The coin pair has been created successfully.'));

        } catch (Exception $exception) {
            DB::rollBack();

            if ($exception->getCode() == 23000) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(RESPONSE_TYPE_ERROR, __('The coin pair already exists.'));
            }

            return redirect()
                ->back()
                ->withInput()
                ->with(RESPONSE_TYPE_ERROR, __('Failed to create coin pair.'));
        }
    }

    public function edit(CoinPair $coinPair): View
    {
        $data['coins'] = Coin::active()->pluck('symbol', 'symbol')->toArray();
        $data['title'] = __('Edit Coin Pair');
        $data['coinPair'] = $coinPair;

        return view('coin_pairs.admin.edit', $data);
    }

    public function update(CoinPairRequest $request, CoinPair $coinPair): RedirectResponse
    {
        $attributes = $request->only('coin', 'base_coin', 'last_price', 'is_active');

        if ($coinPair->is_default && $request->get('is_active') == INACTIVE) {
            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Default coin pair cannot be inactivated.'));
        }


        try {
            if ($coinPair->update($attributes)) {
                cache()->forget('baseCoins');
                return redirect()->route('coin-pairs.edit', $coinPair->name)->with(RESPONSE_TYPE_SUCCESS, __('The coin pair has been updated successfully.'));
            }
        } catch (Exception $exception) {
            if ($exception->getCode() == 23000) {
                return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('The coin pair already exists.'));
            }
        }

        return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to update.'));
    }

    public function destroy(CoinPair $coinPair): RedirectResponse
    {
        if ($coinPair->is_default == ACTIVE) {
            return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to delete.'));
        }

        try {
            if ($coinPair->delete()) {
                cache()->forget('baseCoins');
                return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __('The coin pair has been deleted successfully.'));
            }

            return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to delete.'));
        } catch (Exception $exception) {
            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to delete as the coin pair is being used.'));
        }
    }
}
