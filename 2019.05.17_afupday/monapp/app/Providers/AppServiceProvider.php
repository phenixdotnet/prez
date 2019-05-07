<?php

namespace App\Providers;

use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Console\ConfigCacheCommand;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env("USE_CONSUL")) {
            // Set CONSUL_HTTP_ADDR env to change consul url
            $sf = new \SensioLabs\Consul\ServiceFactory();
            $kv = $sf->get(\SensioLabs\Consul\Services\KVInterface::class);

            $results = $kv->get(config("app.env") . "/monapp");
            $config = json_decode(base64_decode($results->json()[0]["Value"]), true);

            $_ENV = array_merge($_ENV, $config);

            // Rebuild configuration
            if (file_exists($cached = app()->getCachedConfigPath())) {
                unlink($cached);
            }

            $loadConfiguration = new LoadConfiguration();
            $loadConfiguration->bootstrap(app());
        }
    }
}
