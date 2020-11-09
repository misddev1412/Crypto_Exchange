<?php


namespace App\Services\BankManagements;


use App\Models\BankAccount\BankAccount;
use Illuminate\Support\Facades\DB;

class BankAccountService
{
    public function _filterAttributes($request, $isSystemBank = false): array
    {
        $attributes = $request->only([
            'bank_name',
            'iban',
            'swift',
            'reference_number',
            'account_holder',
            'bank_address',
            'account_holder_address',
            'is_verified',
            'is_active',
            'country_id',
        ]);

        if ( !$isSystemBank ) {
            $attributes['user_id'] = auth()->id();
        }

        return $attributes;
    }

    public function getActiveBankAccounts(array $conditions = [])
    {
        $model = BankAccount::select(DB::raw("CONCAT(bank_name, ' - ', iban) AS bank_name"), 'id')->where('is_active', ACTIVE);

        if( !empty($conditions) )
        {
            $model->where($conditions);
        }
        return $model->pluck('bank_name', 'id')->toArray();
    }
}
