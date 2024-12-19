<?php

namespace AbdulQuadri\ActivityLogger\Commands;

use Illuminate\Console\Command;
use AbdulQuadri\ActivityLogger\Models\Activity;
use Carbon\Carbon;

class CleanupActivities extends Command
{
    protected $signature = 'activity-logger:cleanup';
    protected $description = 'Clean up old activity logs';

    public function handle()
    {
        $days = config('activity-logger.retention_days', 90);
        $count = Activity::where('created_at', '<', Carbon::now()->subDays($days))->delete();
        
        $this->info("Successfully deleted {$count} old activity records.");
    }
}


