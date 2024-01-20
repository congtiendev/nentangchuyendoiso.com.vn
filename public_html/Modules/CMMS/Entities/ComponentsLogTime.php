<?php

namespace Modules\CMMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComponentsLogTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'components_id',
        'user_id',
        'hours',
        'minute',
        'date',
        'description',
        'location_id',
        'created_by',
        'company_id',
        'workspace',
    ];
    
    protected static function newFactory()
    {
        return \Modules\CMMS\Database\factories\ComponentsLogTimeFactory::new();
    }
}
