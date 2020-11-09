<?php

namespace App\Http\Controllers\BankAccount;

use App\Http\Controllers\Controller;
use App\Models\BankAccount\BankAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ChangeUserBankAccountStatusController extends Controller
{
    public function change(BankAccount $bankAccount): RedirectResponse
    {
        if ($bankAccount->user_id != Auth::id()) {
            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Invalid bank account id. Please try again.'));
        }
        if ($bankAccount->toggleStatus()) {
            return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __('Successfully  bank account status changed. Please try again.'));
        }
        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to change status. Please try again.'));
    }
}
