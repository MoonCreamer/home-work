<?php

namespace App\Services;

use App\Models\BankAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BankAccountService
{
    public function createAccount(array $data, string $userId)
    {
        return BankAccount::create([
            'id' => Str::uuid(),
            'user_id' => $userId,
            'bank_id' => $data['bank_id'],
            'account_number' => $data['account_number'],
            'money_amount' => $data['money_amount'],
        ]);
    }

    public function getUserAccounts(string $userId)
    {
        return BankAccount::where('user_id', $userId)->get();
    }

    public function getAccountById(string $id, string $userId)
    {
        return BankAccount::where('user_id', $userId)
            ->findOrFail($id);
    }

    public function deleteAccount(string $id, string $userId)
    {
        $account = BankAccount::where('user_id', $userId)
            ->findOrFail($id);

        $account->delete();
    }
}