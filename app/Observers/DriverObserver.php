<?php

namespace App\Observers;

use App\Jobs\DriverUpdated;
use App\Models\Driver;

class DriverObserver
{
    /**
     * Handle the Driver "created" event.
     */
    public function created(Driver $driver): void
    {
        dispatch(new DriverUpdated($driver->user_id))
            ->onConnection('rabbitmq')
            ->onQueue('drivers-updates-queue');
    }

    /**
     * Handle the Driver "updated" event.
     */
    public function updated(Driver $driver): void
    {
        dispatch(new DriverUpdated($driver->user_id))
            ->onConnection('rabbitmq')
            ->onQueue('drivers-updates-queue');
    }

    /**
     * Handle the Driver "deleted" event.
     */
    public function deleted(Driver $driver): void
    {
        //
    }

    /**
     * Handle the Driver "restored" event.
     */
    public function restored(Driver $driver): void
    {
        //
    }

    /**
     * Handle the Driver "force deleted" event.
     */
    public function forceDeleted(Driver $driver): void
    {
        //
    }
}
