<?php

namespace Modules\CMMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Part extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'thumbnail',
        'number',
        'quantity',
        'price',
        'category',
        'supplier_id',
        'components_id',
        'location_id',
        'created_by',
        'company_id',
        'workspace',
        'is_active',
    ];

    
    protected static function newFactory()
    {
        return \Modules\CMMS\Database\factories\PartFactory::new();
    }

    public function getLocation()
    {
        return $this->hasOne('Modules\CMMS\Entities\Location', 'id', 'location_id');
    }
}
