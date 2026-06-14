<?php

namespace App\Observers;

use App\Jobs\PublishModelEvent;
use App\Models\ShiftBreak;

class ShiftBreakObserver
{
    public function created(ShiftBreak $shiftBreak): void {
        PublishModelEvent::dispatch(ShiftBreak::class, $shiftBreak->id, 'created');
    }

    public function updated(ShiftBreak $shiftBreak): void {
        PublishModelEvent::dispatch(ShiftBreak::class, $shiftBreak->id, 'updated');
    }

    public function deleted(ShiftBreak $shiftBreak): void {
        PublishModelEvent::dispatch(ShiftBreak::class, $shiftBreak->id, 'deleted');
    }
}
