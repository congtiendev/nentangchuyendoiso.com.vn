<?php

namespace Modules\CMMS\Events;

use Illuminate\Queue\SerializesModels;

class CreatePms
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $pms;

    public function __construct($request ,$pms)
    {
        $this->request = $request;
        $this->pms = $pms;
    }
}
