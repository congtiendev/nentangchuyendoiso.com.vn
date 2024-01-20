<?php

namespace Modules\CMMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pms extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location_id',
        'description',
        'parts_id',
        'tags',
        'created_by',
        'company_id',
        'workspace',
        'is_active',
    ];
    
    protected static function newFactory()
    {
        return \Modules\CMMS\Database\factories\PmsFactory::new();
    }
    
    public function getLocation()
    {
        return $this->hasOne('Modules\CMMS\Entities\Location', 'id', 'location_id');
    }
}
