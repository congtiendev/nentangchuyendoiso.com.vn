<?php

namespace Modules\CMMS\Events;

use Illuminate\Queue\SerializesModels;

class UpdateCmmspos
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $Pos;
    
    public function __construct($Pos,$request)
    {
        $this->request = $request;
        $this->Pos = $Pos;
    }
}
