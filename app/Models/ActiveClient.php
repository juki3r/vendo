<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActiveClient extends Model
{
    protected $fillable = [
        'device_id',
        'user_id',
        'username',
        'ip',
        'mac',
        'uptime',
        'remaining_seconds'
    ];
}
