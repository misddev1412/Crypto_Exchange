<?php

    namespace App\Http\Controllers\Coin;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Coin\CoinRequest;
    use App\Jobs\Wallet\GenerateUsersWalletsJob;
    use App\Models\Coin\Coin;
    use App\Models\Coin\CoinPair;
    use App\Services\Core\DataTableService;
    use App\Services\Core\FileUploadService;
    use Illuminate\Contracts\View\View;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Validation\ValidationException;

    class AdminCoinController extends Controller
    {
        public function index(): View
        {
            $data['title'] = __('Coin');

            $searchFields = [
                ['symbol', __('Coin')],
                ['name', __('Coin Name')],
                ['type', __('Coin Type')],
                ['is_active', __('Active Status')],
            ];

            $orderFields = [
                ['symbol', __('Coin')],
                ['name', __('Coin Name')],
                ['type', __('Coin Type')],
                ['created_at', __('Created Date')],
            ];

            $select = ['coins.*'];

            $queryBuilder = Coin::select($select)
                ->orderBy('created_at', 'desc');

            $data['dataTable'] = app(DataTableService::class)
                ->setSearchFields($searchFields)
                ->setOrderFields($orderFields)
                ->create($queryBuilder);

            return view('coins.admin.index', $data);
        }

        public function create(): View
        {
            $data['title'] = __('Create Coin');
            $data['coinTypes'] = coin_types();

            return view('coins.admin.create', $data);
        }

        public function store(CoinRequest $request): RedirectResponse
        {
            $attributes = $this->filterFields($request);

            if ($request->hasFile('icon')) {
                $coinIcon = app(FileUploadService::class)
                    ->upload($request->icon, config('commonconfig.path_coin_icon'), '', '', $request->symbol, 'public', 300, 300);
                $attributes['icon'] = $coinIcon;
            }

            if ($coin = Coin::create($attributes)) {
                if (env('QUEUE_CONNECTION', 'sync') === 'sync') {
                    GenerateUsersWalletsJob::dispatchNow($coin);
                } else {
                    GenerateUsersWalletsJob::dispatch($coin);
                }
                return redirect()
                    ->route('coins.edit', $coin->symbol)
                    ->with(RESPONSE_TYPE_SUCCESS, __('The coin has been created successfully.'));
            }
            return redirect()
                ->back()
                ->withInput()
                ->with(RESPONSE_TYPE_ERROR, __("Failed to create coin."));
        }

        private function filterFields(CoinRequest $request): array
        {
            $params = [
                'symbol' => $request->get('symbol'),
                'name' => $request->get('name'),
                'icon' => $request->get('icon'),
                'is_active' => $request->get('is_active'),
                'exchange_status' => $request->get('exchange_status'),
            ];

            if ($request->isMethod('POST')) {
                $params['type'] = $request->get('type');
            }

            return $params;
        }

        public function edit(Coin $coin): View
        {
            $data['title'] = __('Edit Coin');
            $data['coin'] = $coin;
            return view('coins.admin.edit', $data);
        }

        public function update(CoinRequest $request, Coin $coin): RedirectResponse
        {
            $attributes = $this->filterFields($request);

            if ((int)$request->get('is_active') === INACTIVE || (int)$request->get('exchange_status') === INACTIVE) {
                $isDefaultCoinPair = CoinPair::where('is_default', ACTIVE)
                    ->where(function ($query) use ($coin) {
                        $query->where('trade_coin', $coin->symbol)
                            ->orWhere('base_coin', $coin->symbol);
                    })->first();

                if ($isDefaultCoinPair) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with(RESPONSE_TYPE_ERROR, __('This coin is part of default coin pair and it cannot be deactivated.'));
                }
            }


            if ($coin->update($attributes)) {
                return redirect()
                    ->route('coins.edit', $coin->symbol)
                    ->with(RESPONSE_TYPE_SUCCESS, __('The coin has been updated successfully.'));
            }
            return redirect()
                ->back()
                ->withInput()
                ->with(RESPONSE_TYPE_ERROR, __('Failed to update.'));
        }
    }
