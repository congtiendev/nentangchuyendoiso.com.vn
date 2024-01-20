<?php

namespace Modules\CMMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'slug',
        'created_by',
        'company_id',
        'workspace',
        'current_location',
        'lang',
        'interval_time',
        'currency',
        'currency_code',
        'company',
        'city',
        'state',
        'zipcode',
        'country',
        'telephone',
        'logo',
        'is_stripe_enabled',
        'stripe_key',
        'stripe_secret',
        'is_paypal_enabled',
        'paypal_mode',
        'paypal_client_id',
        'paypal_secret_key',
        'invoice_template',
        'invoice_color',
        'invoice_footer_title',
        'invoice_footer_notes',
        'is_active'

    ];
    
    protected static function newFactory()
    {
        return \Modules\CMMS\Database\factories\LocationFactory::new();
    }

    public static function userCurrentLocation()
    {
        // Use a static variable to store the result
        static $cachedLocation = null;
        static $creatorId = null;
        static $getActiveWorkSpace = null;

        // Check if the result is already cached
        if ($cachedLocation !== null && $creatorId == creatorId() && $getActiveWorkSpace == getActiveWorkSpace()) {
            return $cachedLocation;
        }
        
        $location = Location::where('company_id',creatorId())->where('workspace',getActiveWorkSpace())->where('current_location',1)->first();
        if (!is_null($location)) {
                $cachedLocation     = $location->id;
                $creatorId          = creatorId();
                $getActiveWorkSpace = getActiveWorkSpace();
                return $location->id;
        } else {
            return 0;
        }
    }

    public static function addDefaultData($company_id = null,$workspace_id = null)
    {
        if(\Auth::user())
        {
            $check = Location::where('workspace',getActiveWorkSpace())->exists();
            if(!$check)
            {
                    $location = Location::create([
                        'name' => 'location1',
                        'address' => 'First Location',
                        'created_by' => creatorId(),
                        'company_id' => creatorId(),
                        'workspace'   => getActiveWorkSpace(),
                        'current_location'=> 1,   
                    ]);
            }
        }
        else
        {
            $check = Location::where('workspace',1)->exists();
            if(!$check)
            {
                    $location = Location::create([
                        'name' => 'location1',
                        'address' => 'First Location',
                        'created_by' => 2,
                        'company_id' => 2,
                        'workspace'   => 1,
                        'current_location'=> 1,   
                    ]);
            }
        }
    }
}
