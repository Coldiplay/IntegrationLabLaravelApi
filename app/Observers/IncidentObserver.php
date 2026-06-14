<?php

namespace App\Observers;

use App\Jobs\IncidentUpdated;
use App\Jobs\PublishModelEvent;
use App\Models\Incident;

class IncidentObserver
{
    public function created(Incident $incident): void {
        PublishModelEvent::dispatch(Incident::class, $incident->id, 'created');
    }

    public function updated(Incident $incident): void {
        PublishModelEvent::dispatch(Incident::class, $incident->id, 'updated');
    }

    public function deleted(Incident $incident): void {
        PublishModelEvent::dispatch(Incident::class, $incident->id, 'deleted');
    }
}
