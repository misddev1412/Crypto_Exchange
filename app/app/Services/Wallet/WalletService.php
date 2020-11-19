<?php

namespace App\Services\Wallet;

use App\Models\Wallet\Wallet;
use App\Services\Core\DataTableService;

class WalletService
{
    protected $searchFields;
    protected $orderFields;
    protected $whereArray;
    protected $filterFields;
    protected $select;
    protected $downloadableHeadings;

    public function getWallets($userId = null, $isSystemWallet = false): array
    {
        $this->setSearchFields($userId);
        $this->setOrderFields($userId);
        $this->setDownloadableHeadings($userId);
        $this->setWhereArray($userId, $isSystemWallet);
        $this->setFilterFields();
        $this->setSelect($userId);

        $queryBuilder = Wallet::leftJoin('coins', 'wallets.coin_id', '=', 'coins.id')
            ->where($this->whereArray);
        if (is_null($userId)) {
            $queryBuilder->leftJoin('users', 'wallets.user_id', '=', 'users.id')
                ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id');
        }

        $queryBuilder->select($this->select)->orderBy('wallets.created_at', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($this->searchFields)
            ->setOrderFields($this->orderFields)
            ->setFilterFields($this->filterFields)
            ->downloadable($this->downloadableHeadings)
            ->create($queryBuilder);

        return $data;
    }

    public function setSearchFields($userId = null): void
    {
        $searchFields = [
            ['coins.name', __('Wallet Name')],
        ];

        if (is_null($userId)) {
            $searchFields[] = ['users.email', __('User Email')];
            $searchFields[] = ['user_profiles.first_name', __('User First Name')];
            $searchFields[] = ['user_profiles.last_name', __('User Last Name')];
        }
        $this->searchFields = $searchFields;
    }

    public function setOrderFields($userId = null): void
    {


        if (is_null($userId)) {
            $orderFields[] = ['users.email', __('User Email')];
            $orderFields[] = ['user_profiles.first_name', __('User First Name')];
            $orderFields[] = ['user_profiles.last_name', __('User Last Name')];
        }
        $this->orderFields = $orderFields;
    }

    public function setDownloadableHeadings($userId = null): void
    {
        $downloadableHeadings = [
            'coins.symbol' => __('Symbol'),
            'coins.name' => __('Symbol Name'),
            'wallets.primary_balance' => __('Balance'),
            'wallets.address' => __('Address'),
            'coins.type' => __('Type'),
            'coins.total_withdrawal' => __('Total Withdrawal'),
            'coins.total_withdrawal_fee' => __('Total Withdrawal Fee'),
            'coins.total_deposit' => __('Total Deposit'),
            'coins.total_deposit_fee' => __('Total Deposit Fee'),
        ];
        if (is_null($userId)) {
            $downloadableHeadings['user_profiles.first_name'] = __('First Name');
            $downloadableHeadings['user_profiles.last_name'] = __('Last Name');
            $downloadableHeadings['users.email'] = __('email');
        }
        $this->downloadableHeadings = $downloadableHeadings;
    }

    public function setWhereArray($userId = null, $isSystemWallet = false): void
    {
        $whereArray = [
            'is_system_wallet' => $isSystemWallet ? INACTIVE: ACTIVE
        ];
        if (!is_null($userId)) {
            $whereArray['user_id'] = $userId;
        }

        $this->whereArray = $whereArray;
    }

    public function setFilterFields(): void
    {
        $filterFields = [
            ['primary_balance', __('Balance'), 'preset', null,
                [
                    [__('Hide 0(zero) balance'), '>', 0],
                ]
            ],
        ];

        $this->filterFields = $filterFields;
    }

    public function setSelect($userId = null): void
    {
        $select = ['wallets.*', 'coins.symbol', 'coins.name', 'coins.type', 'transaction_status', 'withdrawal_status'];

        if (is_null($userId)) {
            $select[] = 'user_profiles.first_name';
            $select[] = 'user_profiles.last_name';
            $select[] = 'users.email';
        }

        $this->select = $select;
    }

    public function depositToWallet($userId, $value)
    {
        return Wallet::where('user_id', $userId)->where('symbol', 'ONE')->where('is_active', 1)->increment('primary_balance', $value);
    }
}
