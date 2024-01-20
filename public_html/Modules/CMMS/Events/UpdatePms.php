<?php

namespace Modules\CMMS\Events;

use Illuminate\Queue\SerializesModels;

class UpdatePms
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $pms;
    
    public function __construct($pms,$request)
    {
        $this->request = $request;
        $this->pms = $pms;
    }
}
