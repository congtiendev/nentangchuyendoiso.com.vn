<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerFile extends Model
{
    use HasFactory;
    protected $guarded = ['_token'];

    public static $statues = [
        'Chờ phê duyệt',
        'Phê duyệt',
        'Thu hồi',
        'Hủy',
    ];
}
