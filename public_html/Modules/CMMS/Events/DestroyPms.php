<?php

namespace Modules\CMMS\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPms
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $pms;
    
    public function __construct($pms)
    {
        $this->pms = $pms;
    }
}
