<?php

namespace Modules\Hrm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Procedure extends Model
{
    use HasFactory;
    protected $table = "procedures";
    protected $fillable = [
        'id',
        'name',
        'procedure_type',
        'description',
        'created_at',
        'updated_at'
    ];

    public function procedureType()
    {
        return $this->belongsTo(ProcedureType::class, 'procedure_type');
    }   
    
    // protected static function newFactory()
    // {
    //     return \Modules\Hrm\Database\factories\ProcedureFactory::new();
    // }
}
