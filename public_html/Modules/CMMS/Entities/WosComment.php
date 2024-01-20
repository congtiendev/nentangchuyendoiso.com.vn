<?php

namespace Modules\CMMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WosComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'wo_id',
        'description',
        'file',
        'location_id',
        'created_by',
        'company_id',
        'workspace',
    ];
    
    protected static function newFactory()
    {
        return \Modules\CMMS\Database\factories\WosCommentFactory::new();
    }
}
