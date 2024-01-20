<?php

namespace Modules\CMMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkOrderImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'wo_id',
        'image',
        'location_id',
        'created_by',
        'company_id',
        'workspace'
    ];
    
    protected static function newFactory()
    {
        return \Modules\CMMS\Database\factories\WorkOrderImageFactory::new();
    }
}
