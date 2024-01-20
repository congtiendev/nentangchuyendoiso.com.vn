<?php

namespace Modules\CMMS\Events;

use Illuminate\Queue\SerializesModels;

class UpdateComponent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $components;
    
    public function __construct($components,$request)
    {
        $this->request = $request;
        $this->components = $components;
    }
}
