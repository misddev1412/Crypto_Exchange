<?php

namespace App\Http\Controllers\Api\BankAccount;

use App\Http\Controllers\Controller;
use App\Models\BankAccount\BankAccount;
use Illuminate\Support\Facades\Auth;

class UserBankAccountController extends Controller
{
    public function __invoke()
    {
        $bankAccounts = BankAccount::where('user_id', Auth::id())->get();

        $refactorBankAccounts = [];

        foreach($bankAccounts as $bankAccount) {
            $refactorBankAccounts[] = [
                'id' => $bankAccount->id,
                'name' => $bankAccount->bank_name,
                'iban' => $bankAccount->bank_name,
                'swift' => $bankAccount->swift,
                'bankAddress' => $bankAccount->bank_address,
                'referenceNumber' => $bankAccount->reference_number,
                'accountHolderAddress' => $bankAccount->account_holder_address,
                'isVerified' => verification_status($bankAccount->is_verified),
                'isActive' => active_status($bankAccount->is_active),
                'createdAt' => $bankAccount->created_at
            ];
        }

        return response()->json([
            RESPONSE_STATUS_KEY => true,
            RESPONSE_DATA => $refactorBankAccounts,
        ]);
    }
}
