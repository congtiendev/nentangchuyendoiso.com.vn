<?php

namespace Modules\Hrm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcedureType extends Model
{
    use HasFactory;
    protected $table = "procedures_type";
    protected $fillable = [
        'id',
        'name',
        'description',
        'created_at',
        'updated_at'
    ];
    
    // protected static function newFactory()
    // {
    //     return \Modules\Hrm\Database\factories\ProcedureTypeFactory::new();
    // }
}
