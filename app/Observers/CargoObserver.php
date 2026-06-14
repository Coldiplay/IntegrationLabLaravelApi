<?php

namespace App\Observers;

use App\Jobs\PublishModelEvent;
use App\Models\Cargo;

class CargoObserver
{
    public function created(Cargo $cargo): void {
        PublishModelEvent::dispatch(Cargo::class, $cargo->id, 'created');
    }

    public function updated(Cargo $cargo): void {
        PublishModelEvent::dispatch(Cargo::class, $cargo->id, 'updated');
    }

    public function deleted(Cargo $cargo): void {
        PublishModelEvent::dispatch(Cargo::class, $cargo->id, 'deleted');
    }
}
