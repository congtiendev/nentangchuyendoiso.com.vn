<?php

namespace Modules\CMMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CMMS\Entities\ComponentsField;

class Component extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'thumbnail',
        'sku',
        'location_id',
        'parts_id',
        'created_by',
        'company_id',
        'is_active',
        'workspace'
    ];

    protected static function newFactory()
    {
        return \Modules\CMMS\Database\factories\ComponentFactory::new();
    }


    public function getLocation()
    {
        return $this->hasOne('Modules\CMMS\Entities\Location', 'id', 'location_id');
    }
}
