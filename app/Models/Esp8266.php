<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Esp8266 extends Model
{
    protected $fillable = [
        'device_id',
        'user_id',
        'device_status',
        'last_seen',
    ];
}
