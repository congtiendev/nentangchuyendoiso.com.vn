<?php

namespace Modules\CMMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Form extends Model
{
    use HasFactory;

    public $fillable = [
        'pms_id',
        'json',
        'html',
        'created_by',
        'company_id',
        'is_active',
        'workspace'
    ];
    
    protected static function newFactory()
    {
        return \Modules\CMMS\Database\factories\FormFactory::new();
    }
}
