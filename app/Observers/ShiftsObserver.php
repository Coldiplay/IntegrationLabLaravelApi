<?php

namespace App\Observers;

use App\Jobs\ShiftUpdated;
use App\Models\DriversShift;

class ShiftsObserver
{
    /**
     * Handle the DriversShift "created" event.
     */
    public function created(DriversShift $driversShift): void
    {
        dispatch(new ShiftUpdated($driversShift->id))
            ->onConnection('rabbitmq')
            ->onQueue('shifts-updates-queue');
    }

    /**
     * Handle the DriversShift "updated" event.
     */
    public function updated(DriversShift $driversShift): void
    {
        dispatch(new ShiftUpdated($driversShift->id))
            ->onConnection('rabbitmq')
            ->onQueue('shifts-updates-queue');
    }

    /**
     * Handle the DriversShift "deleted" event.
     */
    public function deleted(DriversShift $driversShift): void
    {
        //
    }

    /**
     * Handle the DriversShift "restored" event.
     */
    public function restored(DriversShift $driversShift): void
    {
        //
    }

    /**
     * Handle the DriversShift "force deleted" event.
     */
    public function forceDeleted(DriversShift $driversShift): void
    {
        //
    }
}
