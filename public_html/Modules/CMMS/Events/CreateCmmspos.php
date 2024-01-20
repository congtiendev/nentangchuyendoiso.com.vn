<?php

namespace Modules\CMMS\Events;

use Illuminate\Queue\SerializesModels;

class CreateCmmspos
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $Pos;

    public function __construct($request ,$Pos)
    {
        $this->request = $request;
        $this->Pos = $Pos;
    }
}
