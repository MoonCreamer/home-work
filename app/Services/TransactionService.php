<?php

namespace App\Services;

use App\Models\BankAccount;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionService
{
    public function createTransaction(array $data, string $userId)
    {
        $fromAccount = BankAccount::where('user_id', $userId)
            ->findOrFail($data['from_account']);

        $toAccount = BankAccount::findOrFail($data['to_account']);

        if ($fromAccount->money_amount < $data['amount']) {
            throw new \Exception('Insufficient funds');
        }

        DB::beginTransaction();

        try {
            $fromAccount->decrement('money_amount', $data['amount']);
            $toAccount->increment('money_amount', $data['amount']);

            $transaction = Transaction::create([
                'id' => Str::uuid(),
                'user_id' => $userId,
                'from_account' => $fromAccount->id,
                'to_account' => $toAccount->id,
                'amount' => $data['amount'],
                'comment' => $data['comment'] ?? null,
            ]);

            DB::commit();

            return $transaction;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getUserTransactions(string $userId)
    {
        return Transaction::where('user_id', $userId)->get();
    }
}