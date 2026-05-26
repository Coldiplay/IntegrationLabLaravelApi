<?php

namespace App\Observers;

use App\Jobs\MessageUpdated;
use App\Models\Cargo;

class CargoObserver
{
    /**
     * Handle the Cargo "created" event.
     */
    public function created(Cargo $cargo): void
    {
        dispatch(new MessageUpdated($cargo->id))
            ->onConnection('rabbitmq')
            ->onQueue('cargos-updates-queue');
    }

    /**
     * Handle the Cargo "updated" event.
     */
    public function updated(Cargo $cargo): void
    {
        dispatch(new MessageUpdated($cargo->id))
            ->onConnection('rabbitmq')
            ->onQueue('cargos-updates-queue');
    }

    /**
     * Handle the Cargo "deleted" event.
     */
    public function deleted(Cargo $cargo): void
    {
        //
    }

    /**
     * Handle the Cargo "restored" event.
     */
    public function restored(Cargo $cargo): void
    {
        //
    }

    /**
     * Handle the Cargo "force deleted" event.
     */
    public function forceDeleted(Cargo $cargo): void
    {
        //
    }
}
