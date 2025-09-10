<?php

namespace Drands\LaravelUtils;

use Illuminate\Support\ServiceProvider;

class LaravelUtilsServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register the helpers
        $allHelperFiles = glob(__DIR__ . '/Helpers/*.php');
        foreach ($allHelperFiles as $helperFile) {
            require_once($helperFile);
        }
    }

    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'laravel-utils');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Drands\LaravelUtils\Console\Commands\StorageClear::class,
            ]);
        }
    }
}
