<?php

namespace Modules\CMMS\Events;

use Illuminate\Queue\SerializesModels;

class DestroyLocation
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $location;
    
    public function __construct($location)
    {
        $this->location = $location;
    }
}
