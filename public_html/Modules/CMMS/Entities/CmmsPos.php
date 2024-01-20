<?php

namespace Modules\CMMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CmmsPos extends Model
{
    use HasFactory;

    protected $fillable = [
        'pos_id',
        'parts_id',
        'vendor_id',
        'user_id',
        'pos_date',
        'delivery_date',
        'budgets_id',
        'location_id',
        'created_by',
        'company_id',
        'workspace',
        'is_active',
    ];
    
    

    protected static function newFactory()
    {
        return \Modules\CMMS\Database\factories\CmmsPosFactory::new();
    }

    public function getLocation()
    {
        return $this->hasOne('Modules\CMMS\Entities\Location', 'id', 'location_id');
    }

    public function items()
    {
        return $this->hasMany('Modules\CMMS\Entities\CmmsPosPart', 'pos_id', 'id');
    }
}
