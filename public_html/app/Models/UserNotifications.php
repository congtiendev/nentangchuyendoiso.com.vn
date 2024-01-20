<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotifications extends Model
{
    use HasFactory;
    protected $table = 'user_notifications';
    protected $fillable = [
        'user_id',
        'notification_id',
        'is_read',
    ];
}
