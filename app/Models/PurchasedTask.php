<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class PurchasedTask extends Model
{
    protected $fillable = [
        'user_id',
        'task_id',
        'status',
        'comission',
        'price',
        'task_title',
        'remaining_time'
    ];

    protected $casts = [
        'remaining_time' => 'datetime',
    ];

    public static function totalActiveSpentByUser()
    {
        return self::where('user_id', Auth::id())
                   ->where('remaining_time', '>', Carbon::now())
                   ->sum('price');
    }

public static function totalCommissionEarned()
{
    return self::where('user_id', Auth::id())
               ->where('remaining_time', '<', Carbon::now())
               ->sum('comission');
}

public static function hasReachedTaskLimit()
{
    $user = Auth::user();

    // Define limits based on account_status
    $limits = [
        'VIP1' => 20,
        'VIP2' => 25,
        'VIP3' => 30,
        'VIP4' => 35,
        'VIP5' => 40,
    ];

    $limit = $limits[$user->account_status] ?? 0;

    $activeTaskCount = self::where('user_id', $user->id)
        ->where('remaining_time', '>', Carbon::now())
        ->count();

    return $activeTaskCount >= $limit;
}

}
