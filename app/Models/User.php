<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Transaction;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'contact',
        'refer_code',
        'password',
        'role',
        'dob',
        'gender',
        'country',
        'division',
        'district',
        'city',
        'road',
        'address_note',
        'profile_image',
        'account_status',
        'verification',
        'ver_doc', 
        'refered_by'
    ];

   
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    
    public function getBalanceAttribute()
    {
        return $this->transactions()
            ->where('status', 'accepted')
            ->selectRaw("
                SUM(CASE WHEN type = 'deposit' THEN amount ELSE 0 END) - 
                SUM(CASE WHEN type = 'withdrawal' THEN amount ELSE 0 END) as balance
            ")
            ->value('balance') + \App\Models\PurchasedTask::totalCommissionEarned() + \App\Models\Transaction::referralBonusEarned() ?? 0;
    }


    public function purchasedTasks()
    {
    return $this->hasMany(PurchasedTask::class);
    }

    public function referredUsersCount()
    {
        return self::where('refered_by', $this->refer_code)
                    ->whereNotNull('refered_by')
                    ->count();
    }

}
