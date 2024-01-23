<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractSample extends Model
{
    use HasFactory;
    protected $table = 'contract_sample';
    protected $fillable = [
        'name',
        'content',
        'contract_object',
        'competent_person',
        'description',
        'created_by',
        'contract_type',
        'workspace',
        'created_at',
    ];
}
