<?php

namespace Modules\CMMS\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPart
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $parts;
    
    public function __construct($parts)
    {
        $this->parts = $parts;
    }
}
