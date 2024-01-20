<?php

namespace Modules\CMMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CmmsPosPart extends Model
{
    use HasFactory;

    protected $fillable = [
        'pos_id',
        'parts_id',
        'quantity',
        'tax',
        'discount',
        'price',
        'description',
        'location_id',
        'created_by',
        'company_id',
        'workspace',
        'is_active',
    ];
    
    protected static function newFactory()
    {
        return \Modules\CMMS\Database\factories\CmmsPosPartFactory::new();
    }
}
