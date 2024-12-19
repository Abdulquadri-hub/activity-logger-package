<?php

namespace YourVendor\ActivityLogger;

use Illuminate\Support\ServiceProvider;
use AbdulQuadri\ActivityLogger\Commands\CleanupActivities;
use AbdulQuadri\ActivityLogger\Middleware\TrackUserActivity;

class ActivityLoggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'activity-logger-migrations');

        // Publish config
        $this->publishes([
            __DIR__.'/../config/activity-logger.php' => config_path('activity-logger.php'),
        ], 'activity-logger-config');

        // Register middleware
        $this->app['router']->aliasMiddleware('track.activity', TrackUserActivity::class);

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                CleanupActivities::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/activity-logger.php', 'activity-logger'
        );

        $this->app->singleton('activity-logger', function ($app) {
            return new ActivityLogger();
        });
    }
}
