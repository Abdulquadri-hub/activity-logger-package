<?php

namespace AbdulQuadri\ActivityLogger\Observers;

use AbdulQuadri\ActivityLogger\Models\Activity;

class ActivityObserver
{
    public function created(Activity $activity)
    {
        // Additional processing after activity is created
        if (config('activity-logger.dispatch_events', true)) {
            event('activity-logger.created', $activity);
        }
    }
}