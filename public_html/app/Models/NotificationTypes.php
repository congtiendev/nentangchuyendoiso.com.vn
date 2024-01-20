<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTypes extends Model
{
    use HasFactory;
    protected $table = 'notification_types';
    protected $fillable = [
        'name',
    ];

}
