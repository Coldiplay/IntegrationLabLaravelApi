<?php

namespace App\Observers;

use App\Jobs\ShiftBreakUpdated;
use App\Models\ShiftBreak;

class ShiftBreakObserver
{
    /**
     * Handle the ShiftBreak "created" event.
     */
    public function created(ShiftBreak $shiftBreak): void
    {
        dispatch(new ShiftBreakUpdated($shiftBreak->id))
            ->onConnection('rabbitmq')
            ->onQueue('breaks-updates-queue');
    }

    /**
     * Handle the ShiftBreak "updated" event.
     */
    public function updated(ShiftBreak $shiftBreak): void
    {
        dispatch(new ShiftBreakUpdated($shiftBreak->id))
            ->onConnection('rabbitmq')
            ->onQueue('breaks-updates-queue');
    }

    /**
     * Handle the ShiftBreak "deleted" event.
     */
    public function deleted(ShiftBreak $shiftBreak): void
    {
        //
    }

    /**
     * Handle the ShiftBreak "restored" event.
     */
    public function restored(ShiftBreak $shiftBreak): void
    {
        //
    }

    /**
     * Handle the ShiftBreak "force deleted" event.
     */
    public function forceDeleted(ShiftBreak $shiftBreak): void
    {
        //
    }
}
