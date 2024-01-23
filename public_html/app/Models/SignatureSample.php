<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignatureSample extends Model
{
    use HasFactory;
    protected $table = 'signature_sample';
    protected $fillable = [
        'name',
        'content',
        'signature_object',
        'approver',
        'description',
        'created_by',
        'signature_type',
        'workspace',
        'created_at',
        'updated_at',
    ];
}
