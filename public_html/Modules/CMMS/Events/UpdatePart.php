<?php

namespace Modules\CMMS\Events;

use Illuminate\Queue\SerializesModels;

class UpdatePart
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $parts;
    
    public function __construct($parts,$request)
    {
        $this->request = $request;
        $this->parts = $parts;
    }
}
