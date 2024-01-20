<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshiftDepartment extends Model
{
    use HasFactory;
    protected $table = 'workshift_department';
    protected $fillable = [
        'id',
        'department_id',
        'date',
        'date_of_week',
        'shift',
    ];
}