<?php

namespace AbdulQuadri\ActivityLogger;

use AbdulQuadri\ActivityLogger\Models\Activity;
use Illuminate\Database\Eloquent\Model;

class ActivityLogger
{
    public function log(string $type, ?Model $model = null, ?array $payload = null): Activity
    {
        return Activity::log($type, $model, $payload);
    }

    public function logAuth(string $type, ?Model $user = null): Activity
    {
        return $this->log("auth_{$type}", $user);
    }

    public function getActivities(
        ?int $userId = null,
        ?string $type = null,
        int $limit = 50
    ) {
        $query = Activity::query()
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->when($type, fn($q) => $q->where('type', $type))
            ->latest();

        return $limit ? $query->limit($limit)->get() : $query->get();
    }

    public function getUserActivities(int $userId, int $limit = 50)
    {
        return $this->getActivities($userId, null, $limit);
    }

    public function getModelActivities(Model $model, int $limit = 50)
    {
        return Activity::where('model_type', get_class($model))
            ->where('model_id', $model->id)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function cleanup(int $days = null): int
    {
        $days = $days ?? config('activity-logger.retention_days', 90);
        return Activity::where('created_at', '<', now()->subDays($days))->delete();
    }
}