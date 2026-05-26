<?php

namespace App\Observers;

use App\Jobs\IncidentUpdated;
use App\Models\Incident;

class IncidentObserver
{
    /**
     * Handle the Incident "created" event.
     */
    public function created(Incident $incident): void
    {
        dispatch(new IncidentUpdated($incident->id))
            ->onConnection('rabbitmq')
            ->onQueue('incidents-updates-queue');
    }

    /**
     * Handle the Incident "updated" event.
     */
    public function updated(Incident $incident): void
    {
        dispatch(new IncidentUpdated($incident->id))
            ->onConnection('rabbitmq')
            ->onQueue('incidents-updates-queue');
    }

    /**
     * Handle the Incident "deleted" event.
     */
    public function deleted(Incident $incident): void
    {
        //
    }

    /**
     * Handle the Incident "restored" event.
     */
    public function restored(Incident $incident): void
    {
        //
    }

    /**
     * Handle the Incident "force deleted" event.
     */
    public function forceDeleted(Incident $incident): void
    {
        //
    }
}
