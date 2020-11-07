<?php

namespace Jybtx\RsaCryptAes\Providers;

use Illuminate\Support\ServiceProvider;
use Jybtx\RsaCryptAes\EncryptionAndDecryption;
use Jybtx\RsaCryptAes\Console\AesEncryptSecretCommand;

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
            __DIR__."/../../config/crypt.php" => config_path('crypt.php'),
        ],'crypt');
    }

    /**
     * Merge configuration.
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/crypt.php', 'crypt'
        );
    }
    /**
     * [singleton description]
     * @author jybtx
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
     * [get Artisan Command description]
     * @author jybtx
     * @date   2019-10-09
     * @return [type]     [description]
     */
    public function getArtisanCommand()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AesEncryptSecretCommand::class,
            ]);
        }
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

        $this->getArtisanCommand();
    }


}