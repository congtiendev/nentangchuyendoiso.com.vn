<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Hrm\Entities\Employee;

class ActivityLogEmployee extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'user_type', 'employee_id', 'log_type', 'remark',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

}
