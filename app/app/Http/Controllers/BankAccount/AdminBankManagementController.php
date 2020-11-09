<?php

namespace App\Http\Controllers\BankAccount;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankManagement\BankAccountRequest;
use App\Models\BankAccount\BankAccount;
use App\Models\Deposit\WalletDeposit;
use App\Services\BankManagements\BankAccountService;
use App\Services\Core\CountryService;
use App\Services\Core\DataTableService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminBankManagementController extends Controller
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
            ['reference_number', __('Reference Number')],
            ['account_holder_address', __('Account Holder Address')],
        ];

        $orderFields = [
            ['bank_name', __('Bank Name')],
            ['iban', __('IBAN')],
            ['swift', __('SWIFT / BIC')],
            ['reference_number', __('Reference Number')],
            ['account_holder', __('Account Holder')],
            ['is_verified', __('Verification')],
            ['is_active', __('Status')],
        ];
        $data['title'] = __('Bank Accounts');

        $queryBuilder = BankAccount::query()
            ->withDepositCount()
            ->whereNull('user_id')
            ->orderByDesc('created_at');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->create($queryBuilder);

        return view('bank_managements.admin.index', $data);
    }

    public function create(): View
    {
        $data['countries'] = app(CountryService::class)->getCountries();
        $data['title'] = __('Create System Bank Account');
        return view('bank_managements.admin.create', $data);
    }

    public function store(BankAccountRequest $request): RedirectResponse
    {
        $attributes = $this->service->_filterAttributes($request, true);
        $created = BankAccount::create($attributes);
        if ($created) {
            return redirect()->route('system-banks.index')->with(RESPONSE_TYPE_SUCCESS, __('The system bank account has been added successfully.'));
        }
        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to add the system bank account. Please try again.'))->withInput();
    }

    public function edit(BankAccount $systemBank)
    {
        if( $systemBank->deposits()->count() > 0 ) {
            return redirect()->route('system-banks.index')
                ->with(RESPONSE_TYPE_ERROR, __('The system bank account can not be modified.'));
        }

        $data['countries'] = app(CountryService::class)->getCountries();
        $data['title'] = __('Edit System Bank Account');
        $data['systemBank'] = $systemBank;

        return view('bank_managements.admin.edit', $data);
    }

    public function update(BankAccountRequest $request, BankAccount $systemBank): RedirectResponse
    {
        if( $systemBank->deposits()->count() > 0 ) {
            return redirect()->route('system-banks.index')
                ->with(RESPONSE_TYPE_ERROR, __('The system bank account can not be modified.'));
        }

        $attributes = $this->service->_filterAttributes($request, true);

        if ($systemBank->update($attributes)) {
            return redirect()->route('system-banks.edit', $systemBank->id)->with(RESPONSE_TYPE_SUCCESS, __('The system bank account has been updated successfully.'));
        }

        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to update the system bank account. Please try again.'))->withInput();
    }

    public function destroy(BankAccount $systemBank): RedirectResponse
    {
        if( $systemBank->deposits()->count() > 0 ) {
            return redirect()->route('system-banks.index')
                ->with(RESPONSE_TYPE_ERROR, __('The system bank account can not be deleted.'));
        }

        if ( $systemBank->delete() ) {
            return redirect()->route('system-banks.index')->with(RESPONSE_TYPE_SUCCESS, __('The system bank account has been deleted successfully.'));
        }

        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to delete the system bank account. Please try again.'))->withInput();
    }
}
