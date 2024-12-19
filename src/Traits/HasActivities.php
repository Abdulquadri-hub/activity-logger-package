<?php

namespace AbdulQuadri\ActivityLogger\Traits;

use AbdulQuadri\ActivityLogger\Models\Activity;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasActivities
{
    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'model');
    }

    public function logActivity(string $type, ?array $payload = null): Activity
    {
        return Activity::log($type, $this, $payload);
    }
}