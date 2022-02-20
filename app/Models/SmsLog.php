<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'sms_type',
        'user_id',
        'mobile',
        'title',
        'description',
        'status',
        'ip_address',
    ];
}
