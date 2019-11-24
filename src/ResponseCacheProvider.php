<?php

namespace Armen\ResponseCache;

use Armen\ResponseCache\Console\Commands\ResponseCacheKiller;
use Illuminate\Support\ServiceProvider;

class ResponseCacheProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/httpresponsecache.php', 'httpresponsecache');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/routes/web.php';

        $this->publishes([
            __DIR__ . '/../config/httpresponsecache.php' => config_path('httpresponsecache.php'),
        ], 'config');

        $this->commands([ResponseCacheKiller::class]);
    }
}
