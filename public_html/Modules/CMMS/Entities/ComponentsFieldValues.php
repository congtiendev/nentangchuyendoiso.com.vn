<?php

namespace Modules\CMMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComponentsFieldValues extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_id',
        'field_id',
        'value',
        'created_by',
        'company_id',
        'is_active',
        'workspace'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    public function getFieldByName($name,$id){
        $value = '';
        $componentsFields = ComponentsField::where(['name' => $name, 'module' => 'Assets'])->first();
        if(!is_null($componentsFields)){
            $componentsFieldValues = ComponentsFieldValues::where(['field_id' => $componentsFields->id, 'record_id' => $id])->first();
            if(!is_null($componentsFieldValues)){
                $value = $componentsFieldValues->value; 
            }
        }
        return $value;
    }
}
