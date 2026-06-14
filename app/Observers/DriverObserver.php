<?php

namespace App\Observers;

use App\Jobs\PublishModelEvent;
use App\Models\Driver;

class DriverObserver
{
    public function created(Driver $driver): void {
        PublishModelEvent::dispatch(Driver::class, $driver->user_id, 'created');
    }

    public function updated(Driver $driver): void {
        PublishModelEvent::dispatch(Driver::class, $driver->user_id, 'updated');
    }

    public function deleted(Driver $driver): void {
        PublishModelEvent::dispatch(Driver::class, $driver->user_id, 'deleted');
    }
}
