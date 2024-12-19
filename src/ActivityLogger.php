<?php

namespace YourVendor\ActivityLogger;

use AbdulQuadri\ActivityLogger\Models\Activity;
use Illuminate\Database\Eloquent\Model;

class ActivityLogger
{
    public function log(string $type, ?Model $model = null, ?array $payload = null)
    {
        return Activity::log($type, $model, $payload);
    }

    public function logAuth(string $type, ?Model $user = null)
    {
        return $this->log("auth_{$type}", $user);
    }

    public function logJob(string $type, Model $job)
    {
        return $this->log("job_{$type}", $job, [
            'job_title' => $job->title ?? null,
            'job_status' => $job->status ?? null
        ]);
    }

    public function getUserActivities($userId, $limit = 50)
    {
        return Activity::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
