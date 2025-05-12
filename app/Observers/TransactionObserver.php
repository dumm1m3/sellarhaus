<?php
namespace App\Observers;

use App\Models\Transaction;
use App\Models\User;

class TransactionObserver
{
    public function saved(Transaction $transaction)
    {
        // Only trigger for accepted transactions
        if ($transaction->status !== 'accepted') {
            return;
        }

        $user = $transaction->user;

        // Recalculate balance
        $balance = $user->balance;

        // Generate refer_code if not present
        if ($balance >= 30.00 && is_null($user->refer_code)) {
            do {
                $code = strtolower(str_replace(' ', '', $user->name)) . rand(1000, 9999);
            } while (User::where('refer_code', $code)->exists());

            $user->refer_code = $code;
        }

        $user->save();
    }
}
