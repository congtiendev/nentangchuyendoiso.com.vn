<?php

namespace Modules\Hrm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllowanceOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\AllowanceOptionFactory::new();
    }
}
