<?php

namespace Modules\CMMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CMMS\Entities\ComponentsField;

class ComponentsField extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'module',
        'created_by',
        'company_id',
        'is_active',
    ];
    
    public static function addDefaultData($company_id = null,$workspace_id = null)
    {
 
        $componentFields = [
            'Component_Tag' => 'text',
            'Category' => 'text',
            'Assigned Date' => 'date',
            'Description' => 'text',
            'Link' => 'text',
            'Model' => 'text',
            'Brand' => 'text',
            'Operating Hours' => 'time',
            'Original Cost' => 'number',
            'Purchase Cost' => 'number',
            'Serial Number' => 'number',
            'Service Contact' => 'text',
            'Warranty Exp Date' => 'date',
            'Warranty Document' => 'file/document',
            'Documents and Picture' => 'multiple_files/document',
        ];

        foreach ($componentFields as $key => $value) {
            $data = [
                'name' => $key,
                'type' => $value,
                'module' => 'Components',
                'created_by'=>0,
                'company_id'=>0,
                'is_active'=>1
            ];
            $checkIfExist = ComponentsField::where(['name' => $key, 'type' => $value, 'module' => 'Components'])->first();
            if (is_null($checkIfExist)) {
                ComponentsField::create($data);
            }
        }
    }

    protected static function newFactory()
    {
        return \Modules\CMMS\Database\factories\ComponentsFieldFactory::new();
    }
}
