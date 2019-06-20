<?php

namespace AaronSchmied\ModelHistory\Providers;

use AaronSchmied\ModelHistory\ChangeRecorder;
use AaronSchmied\ModelHistory\Contracts\ChangeRecorder as ChangeRecorderContract;
use Illuminate\Support\ServiceProvider;

class ModelHistoryServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/modelhistory.php' => config_path('modelhistory.php')
                         ], 'config');

        $this->mergeConfigFrom(__DIR__.'/../../config/modelhistory.php', 'modelhistory');

        if (! class_exists('CreateModelHistoryTable')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                    __DIR__.'/../../migrations/create_model_history_table.php' =>
                        database_path("/migrations/{$timestamp}_create_model_history_table.php"),
                ], 'migrations');
        }
    }

    /**
     * Provide the change recorder via ioc.
     *
     * @return array
     */
    public function provides()
    {
        return [
            ChangeRecorderContract::class,
        ];
    }

    /**
     * Register the provided classes.
     */
    public function register()
    {
        $this->app->singleton(ChangeRecorderContract::class, function ($app) {
            $changeRecorderClass = $app['config']['modelhistory']['change_recorder'] ?? ChangeRecorder::class;
            return new $changeRecorderClass;
        });
    }
}
