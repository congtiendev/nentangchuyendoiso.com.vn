<?php

namespace Modules\CMMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WosInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'wo_id',
        'invoice_cost',
        'description',
        'invoice_file',
        'location_id',
        'created_by',
        'company_id',
        'workspace',
    ];
    
    protected static function newFactory()
    {
        return \Modules\CMMS\Database\factories\WosInvoiceFactory::new();
    }
}
