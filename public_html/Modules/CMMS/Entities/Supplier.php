<?php

namespace Modules\CMMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact',
        'email',
        'phone',
        'address',
        'components_id',
        'parts_id',
        'image',
        'location_id',
        'created_by',
        'company_id',
        'workspace',
        'is_active',
    ];
    
    protected static function newFactory()
    {
        return \Modules\CMMS\Database\factories\SupplierFactory::new();
    }

    public function getLocation()
    {
        return $this->hasOne('Modules\CMMS\Entities\Location', 'id', 'location_id');
    }
}
