<?php

namespace AbdulQuadri\ActivityLogger;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use AbdulQuadri\ActivityLogger\Commands\CleanupActivities;
use AbdulQuadri\ActivityLogger\Middleware\TrackUserActivity;
use AbdulQuadri\ActivityLogger\Models\Activity;
use AbdulQuadri\ActivityLogger\Observers\ActivityObserver;

class ActivityLoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        // Register migrations
        if ($this->app->runningInConsole()) {
            $this->registerMigrations();
            $this->registerPublishing();
            $this->registerCommands();
        }

        // Register routes
        $this->registerRoutes();

        // Register middleware
        $this->registerMiddleware();

        // Register observers
        $this->registerObservers();

        // Register views
        $this->registerViews();

        // Register translations
        $this->registerTranslations();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        // Merge configurations
        $this->mergeConfigFrom(
            __DIR__.'/../config/activity-logger.php', 'activity-logger'
        );

        // Register the main class to use with the facade
        $this->app->singleton('activity-logger', function ($app) {
            return new ActivityLogger();
        });

        // Register the activity service
        $this->app->singleton(ActivityLogger::class, function ($app) {
            return new ActivityLogger();
        });
    }

    /**
     * Register the package migrations.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        $migrations = [
            'create_activities_table.php.stub' => 'CreateActivitiesTable',
            // Add future migrations here
        ];

        foreach ($migrations as $migrationFile => $className) {
            if (!class_exists($className)) {
                $timestamp = date('Y_m_d_His', time());
                
                $this->publishes([
                    __DIR__."/../database/migrations/$migrationFile" 
                    => database_path("migrations/{$timestamp}_".str_replace('.stub', '', $migrationFile)),
                ], 'activity-logger-migrations');
                
                // Add small delay to ensure unique timestamps
                usleep(1000000);
            }
        }
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        // Config
        $this->publishes([
            __DIR__.'/../config/activity-logger.php' => config_path('activity-logger.php'),
        ], 'activity-logger-config');

        // Views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/activity-logger'),
        ], 'activity-logger-views');

        // Translations
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/activity-logger'),
        ], 'activity-logger-translations');

        // Assets
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/activity-logger'),
        ], 'activity-logger-assets');
    }

    /**
     * Register the package's commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->commands([
            CleanupActivities::class,
        ]);
    }

    /**
     * Register the package's routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        if (config('activity-logger.routes.enabled', true)) {
            Route::group($this->routeConfiguration(), function () {
                $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
            });

            if (config('activity-logger.routes.api_enabled', true)) {
                Route::group($this->apiRouteConfiguration(), function () {
                    $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
                });
            }
        }
    }

    /**
     * Get the route group configuration array.
     *
     * @return array
     */
    protected function routeConfiguration()
    {
        return [
            'prefix' => config('activity-logger.routes.prefix', 'activity-logger'),
            'middleware' => config('activity-logger.routes.middleware', ['web']),
        ];
    }

    /**
     * Get the API route group configuration array.
     *
     * @return array
     */
    protected function apiRouteConfiguration()
    {
        return [
            'prefix' => config('activity-logger.routes.api_prefix', 'api/activity-logger'),
            'middleware' => config('activity-logger.routes.api_middleware', ['api']),
        ];
    }

    /**
     * Register the package's middleware.
     *
     * @return void
     */
    protected function registerMiddleware()
    {
        // Global middleware
        if (config('activity-logger.middleware.global', false)) {
            $this->app['router']->pushMiddlewareToGroup('web', TrackUserActivity::class);
        }

        // Named middleware
        $this->app['router']->aliasMiddleware('track.activity', TrackUserActivity::class);
    }

    /**
     * Register the package's observers.
     *
     * @return void
     */
    protected function registerObservers()
    {
        if (config('activity-logger.observers.enabled', true)) {
            Activity::observe(ActivityObserver::class);
        }
    }

    /**
     * Register the package's views.
     *
     * @return void
     */
    protected function registerViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'activity-logger');
    }

    /**
     * Register the package's translations.
     *
     * @return void
     */
    protected function registerTranslations()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'activity-logger');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['activity-logger', ActivityLogger::class];
    }
}