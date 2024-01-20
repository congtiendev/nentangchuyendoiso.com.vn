<?php

namespace Modules\CMMS\Events;

use Illuminate\Queue\SerializesModels;

class DestroyComponent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $components;
    
    public function __construct($components)
    {
        $this->components = $components;
    }
}
