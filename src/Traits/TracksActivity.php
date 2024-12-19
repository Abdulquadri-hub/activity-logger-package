<?php

namespace AbdulQuadri\ActivityLogger\Traits;

use AbdulQuadri\ActivityLogger\Models\Activity;

trait TracksActivity
{
    protected static function bootTracksActivity()
    {
        if (config('activity-logger.track_model_changes', true)) {
            static::created(function ($model) {
                $model->logModelActivity('created');
            });

            static::updated(function ($model) {
                $model->logModelActivity('updated');
            });

            static::deleted(function ($model) {
                $model->logModelActivity('deleted');
            });
        }
    }

    protected function logModelActivity(string $event): void
    {
        Activity::log(
            $this->getActivityType($event),
            $this,
            $this->getActivityPayload($event)
        );
    }

    protected function getActivityType(string $event): string
    {
        return $this->activityType ?? class_basename($this) . "_{$event}";
    }

    protected function getActivityPayload(string $event): array
    {
        $payload = [
            'attributes' => $this->getAttributes(),
        ];

        if ($event === 'updated') {
            $payload['changes'] = $this->getChanges();
        }

        return $payload;
    }
}

