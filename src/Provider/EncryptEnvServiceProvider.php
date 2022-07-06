<?php

namespace mrgswift\EncryptEnv\Provider;

use Illuminate\Support\ServiceProvider;

class EncryptEnvServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../Helper/secEnv.php' => app_path('Helpers/secEnv.php')
        ], 'helper');

        $this->publishes([
            __DIR__.'/../../config/encryptenv.php' => config_path('encryptenv.php')
        ], 'config');

        $this->publishes([
            __DIR__.'/../Console/EncryptEnvValues.php' => app_path('Console/Commands/EncryptEnvValues.php')
        ], 'console');

        $this->publishes([
            __DIR__.'/../Console/SecEnvConsoleCommand.php' => app_path('Console/Commands/SecEnvConsoleCommand.php')
        ], 'console');
    }

    public function register()
    {
        //
    }
}