<?php

namespace Modules\CMMS\Events;

use Illuminate\Queue\SerializesModels;

class CreateComponent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $components;

    public function __construct($request ,$components)
    {
        $this->request = $request;
        $this->components = $components;
    }
}
