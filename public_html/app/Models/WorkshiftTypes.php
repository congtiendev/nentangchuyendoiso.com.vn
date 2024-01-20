<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshiftTypes extends Model
{
    use HasFactory;
    protected $table = 'workshift_types';
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
    ];
}
