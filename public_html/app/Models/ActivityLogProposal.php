<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Account\Entities\Customer;
use Modules\Hrm\Entities\Employee;

class ActivityLogProposal extends Model
{
    use HasFactory;
    protected $fillable = [
        'action_type' ,'user_id', 'user_type', 'customer_id', 'log_type', 'remark',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
