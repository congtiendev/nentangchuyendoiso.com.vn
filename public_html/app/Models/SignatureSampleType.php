<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignatureSampleType extends Model
{
    use HasFactory;
    protected $table = 'signature_sample_type';
    protected $fillable = [
        'name',
        'description',
        'workspace',
    ];
}
