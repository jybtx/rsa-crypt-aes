<?php

namespace Jybtx\RsaCryptAes\Providers;

use Illuminate\Support\ServiceProvider;

use Jybtx\RsaCryptAes\EncryptionAndDecryption;

class CryptServiceProvider extends ServiceProvider
{
	
	/**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfig();
    }
    /**
     * Configure package paths.
     */
    private function configurePaths()
    {
        $this->publishes([
            __DIR__."/../config/crypt.php" => config_path('crypt.php'),
        ]);
    }

    /**
     * Merge configuration.
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/crypt.php', 'crypt'
        );
    }
    /**
     * [singleton description]
     * @author 蒋岳
     * @date   2019-09-21
     * @param  string     $value [description]
     * @return [type]            [description]
     */
    private function getRegisterSingleton()
    {
        $this->app->singleton('RsaCryptAes', function () {
            return new EncryptionAndDecryption();
        });
    }

    /**
     * Register any application services.
     *  
     * @return void
     */
    public function register()
    {
        $this->configurePaths();        

        $this->getRegisterSingleton();
    }


}