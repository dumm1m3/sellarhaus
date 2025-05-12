<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';        // your table name
    protected $primaryKey = 'serial';          // your custom primary key
    public $incrementing = true;               // it's auto-incremented
    public $timestamps = true;                 // you have created_at

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;                   // no updated_at in DB

    protected $fillable = [
        'notification_subject',
        'notification_body',
    ];
}