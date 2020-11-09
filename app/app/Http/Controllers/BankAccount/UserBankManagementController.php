<?php

namespace App\Http\Controllers\BankAccount;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankManagement\BankAccountRequest;
use App\Models\BankAccount\BankAccount;
use App\Services\BankManagements\BankAccountService;
use App\Services\Core\CountryService;
use App\Services\Core\DataTableService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserBankManagementController extends Controller
{
    protected $service;

    public function __construct(BankAccountService $service)
    {
        $this->service = $service;
    }

    public function index(): View
    {
        $searchFields = [
            ['bank_name', __('Bank Name')],
            ['iban', __('IBAN')],
            ['swift', __('SWIFT / BIC')],
            ['account_holder', __('Account Holder')],
            ['bank_address', __('Bank Address')],
            ['account_holder_address', __('Account Holder Address')],
        ];

        $orderFields = [
            ['bank_name', __('Bank Name')],
            ['iban', __('IBAN')],
            ['swift', __('SWIFT / BIC')],
            ['account_holder', __('Account Holder')],
            ['is_verified', __('Verification')],
            ['is_active', __('Status')],
        ];

        $select = ['bank_accounts.*'];
        $data['title'] = __('Bank Accounts');

        $queryBuilder = BankAccount::select($select)
            ->where(['user_id' => Auth::id()])
            ->orderBy('id', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->create($queryBuilder);

        return view('bank_managements.user.index', $data);
    }

    public function create(): View
    {
        $data['countries'] = app(CountryService::class)->getCountries();
        $data['title'] = __('Create Bank Account');
        return view('bank_managements.user.create', $data);
    }

    public function store(BankAccountRequest $request): RedirectResponse
    {
        $attributes = $this->service->_filterAttributes($request);
        $created = BankAccount::create($attributes);

        if ($created) {
            return redirect()->route('bank-accounts.index')->with(RESPONSE_TYPE_SUCCESS, __('The bank account has been added successfully.'));
        }
        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to add the bank account. Please try again.'))->withInput();
    }

    public function edit(BankAccount $bankAccount): View
    {
        abort_if($bankAccount->user_id != Auth::id() || $bankAccount->is_verified == ACTIVE, 404);

        $data['countries'] = app(CountryService::class)->getCountries();
        $data['title'] = __('Edit Bank Account');
        $data['bankAccount'] = $bankAccount;

        return view('bank_managements.user.edit', $data);
    }

    public function update(BankAccountRequest $request, BankAccount $bankAccount): RedirectResponse
    {
        if ($bankAccount->user_id != Auth::id() || $bankAccount->is_verified == ACTIVE) {
            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('The Invalid bank account id. Please try again'))->withInput();
        }

        $attributes = $this->service->_filterAttributes($request);
        if ($bankAccount->update($attributes)) {
            return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __('The bank account has been updated successfully.'))->withInput();
        }
        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to update the bank account. Please try again.'))->withInput();
    }

    public function destroy(BankAccount $bankAccount): RedirectResponse
    {
        abort_if($bankAccount->user_id != Auth::id(), 404 || $bankAccount->is_verified == ACTIVE);
        if ($bankAccount->delete()) {
            return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __("The bank account has been deleted successfully."));
        }
        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __("Failed to delete the bank account."));
    }
}
