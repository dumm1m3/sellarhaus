<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet_name',
        'wallet_number',
        'transaction_id',
        'amount',
        'type',
        'status',
        'date',
        'remarks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function referralBonusEarned()
{
    $refCode = Auth::user()->refer_code;
    $status = Auth::user()->account_status;

    // Get all users referred by the current user
    $referredUsers = User::where('refered_by', $refCode)->get();

    $bonus = 0;

    foreach ($referredUsers as $user) {
        // Get first approved deposit transaction
        $firstDeposit = self::where('user_id', $user->id)
            ->where('type', 'deposit')
            ->where('status', 'accepted')
            ->orderBy('created_at')
            ->first();

        if ($firstDeposit) {
            $percentage = match ($status) {
                'VIP1' => 0.05,
                'VIP2' => 0.07,
                'VIP3' => 0.09,
                'VIP4' => 0.12,
                'VIP5' => 0.15,
                default => 0,
            };

            $bonus += $firstDeposit->amount * $percentage;
        }
    }

    return $bonus;
}
}

