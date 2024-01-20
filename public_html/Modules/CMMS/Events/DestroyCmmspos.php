<?php

namespace Modules\CMMS\Events;

use Illuminate\Queue\SerializesModels;

class DestroyCmmspos
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $Pos;
    
    public function __construct($Pos)
    {
        $this->Pos = $Pos;
    }
}
