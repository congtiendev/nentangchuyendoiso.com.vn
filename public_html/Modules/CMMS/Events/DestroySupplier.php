<?php

namespace Modules\CMMS\Events;

use Illuminate\Queue\SerializesModels;

class DestroySupplier
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $suppliers;
    
    public function __construct($suppliers)
    {
        $this->suppliers = $suppliers;
    }
}
