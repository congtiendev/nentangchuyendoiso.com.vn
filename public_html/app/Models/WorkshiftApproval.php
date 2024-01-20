<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshiftApproval extends Model
{
    use HasFactory;
    protected $table = 'workshift_approval';
    protected $fillable = [
        'workshift_id',
        'user_id',
        'shift',
        'date',
        'reason',
        'status',
        'approved_by',
    ];
}
