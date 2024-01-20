<?php

namespace Modules\CMMS\Events;

use Illuminate\Queue\SerializesModels;

class CreateSupplier
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $suppliers;

    public function __construct($request ,$suppliers)
    {
        $this->request = $request;
        $this->suppliers = $suppliers;
    }
}
