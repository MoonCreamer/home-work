<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_account' => 'required|uuid|exists:bank_accounts,id',
            'to_account' => 'required|uuid|exists:bank_accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'comment' => 'nullable|string|max:255',
        ]);

        try {
            $transaction = $this->transactionService->createTransaction(
                $request->all(),
                auth()->id()
            );

            return response()->json(['message' => 'Transaction successful', 'transaction' => $transaction], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function index()
    {
        $transactions = $this->transactionService->getUserTransactions(auth()->id());

        return response()->json($transactions);
    }
}
