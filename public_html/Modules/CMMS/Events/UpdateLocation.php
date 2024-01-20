<?php

namespace Modules\CMMS\Events;

use Illuminate\Queue\SerializesModels;

class UpdateLocation
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $location;
    
    public function __construct($location,$request)
    {
        $this->request = $request;
        $this->location = $location;
    }
}
