<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    protected $bankAccountService;

    public function __construct(BankAccountService $bankAccountService)
    {
        $this->bankAccountService = $bankAccountService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_id' => 'required|uuid|exists:banks,id',
            'account_number' => 'required|string|unique:bank_accounts,account_number',
            'money_amount' => 'required|numeric|min:0',
        ]);

        $account = $this->bankAccountService->createAccount(
            $request->all(),
            auth()->id()
        );

        return response()->json($account, 201);
    }

    public function index()
    {
        $accounts = $this->bankAccountService->getUserAccounts(auth()->id());

        return response()->json($accounts);
    }

    public function show($id)
    {
        $account = $this->bankAccountService->getAccountById($id, auth()->id());

        return response()->json($account);
    }

    public function destroy($id)
    {
        $this->bankAccountService->deleteAccount($id, auth()->id());

        return response()->json(['message' => 'Bank account deleted successfully']);
    }
}
