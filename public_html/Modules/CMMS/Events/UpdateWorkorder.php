<?php

namespace Modules\CMMS\Events;

use Illuminate\Queue\SerializesModels;

class UpdateWorkorder
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $workorder;
    
    public function __construct($workorder,$request)
    {
        $this->request = $request;
        $this->workorder = $workorder;
    }
}
