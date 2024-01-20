<?php

namespace Modules\CMMS\Events;

use Illuminate\Queue\SerializesModels;

class CreatePart
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $parts;

    public function __construct($request ,$parts)
    {
        $this->request = $request;
        $this->parts = $parts;
    }
}
