<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomNotification extends Model
{
    use HasFactory;
    protected $table = 'custom_notification';
    protected $fillable = [
        'title',
        'content',
        'link',
        'from',
        'send_to',
        'type'
    ];
}
