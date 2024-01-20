<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShift extends Model
{
    use HasFactory;
    protected $table = 'workshift';
    protected $fillable = ['user_id','date', 'day_of_week', 'shift','status'];
}
