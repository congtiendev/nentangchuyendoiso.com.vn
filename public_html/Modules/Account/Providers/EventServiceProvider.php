<?php

namespace Modules\Account\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;
use Modules\Account\Listeners\ProductUpdate;
use Modules\ProductService\Events\UpdateProduct;

class EventServiceProvider extends Provider
{

    // protected $listen = [
    //     UpdateProduct::class =>[
    //         ProductUpdate::class
    //     ],
    // ];
    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return true;
    }

    /**
     * Get the listener directories that should be used to discover events.
     *
     * @return array
     */
    protected function discoverEventsWithin()
    {
        return [
            __DIR__ . '/../Listeners',
        ];
    }
}
