<?php

namespace Modules\CMMS\Events;

use Illuminate\Queue\SerializesModels;

class UpdateSupplier
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $suppliers;
    
    public function __construct($suppliers,$request)
    {
        $this->request = $request;
        $this->suppliers = $suppliers;
    }
}
