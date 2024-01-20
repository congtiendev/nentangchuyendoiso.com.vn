<?php

namespace Modules\CMMS\Events;

use Illuminate\Queue\SerializesModels;

class CreateWorkrequest
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $workorder;

    public function __construct($request ,$workorder)
    {
        $this->request = $request;
        $this->workorder = $workorder;
    }
}
