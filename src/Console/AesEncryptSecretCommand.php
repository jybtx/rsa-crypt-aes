<?php
namespace Jybtx\RsaCryptAes\Console;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class AesEncryptSecretCommand extends Command
{

	/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jybtx:secret
				    {--show : Display the key instead of modifying files}';

	 /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generating 16-bit vector strings';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$key = $this->generateRandomKey();
    	if ($this->option('show')) {
            $this->comment($key);
            return;
        }
        if (file_exists($path = $this->envPath()) === false) {
            return $this->displayKey($key);
        }
        if (Str::contains(file_get_contents($path), 'HEX_IV') === false) {
            // create new entry
            file_put_contents($path, PHP_EOL."HEX_IV=$key".PHP_EOL, FILE_APPEND);
        } else {
            // update existing entry
            file_put_contents($path, str_replace(
                'HEX_IV='.$this->laravel['config']['crypt.aes_encrypt_key.hex_iv'],
                'HEX_IV='.$key, file_get_contents($path)
            ));
        }
        $this->displayKey($key);
    }
    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected function generateRandomKey()
    {
        return 'base64:'.base64_encode(
            Str::random(
                openssl_cipher_iv_length(config('crypt.aes_encrypt_key.method'))
            )
        );
    }
    /**
     * Display the key.
     *
     * @param  string  $key
     *
     * @return void
     */
    protected function displayKey($key)
    {
        $this->laravel['config']['crypt.aes_encrypt_key.hex_iv'] = $key;
        $this->info("iv key set successfully.");
    }
    /**
     * Get the .env file path.
     *
     * @return string
     */
    protected function envPath()
    {
        if (method_exists($this->laravel, 'environmentFilePath')) {
            return $this->laravel->environmentFilePath();
        }
        // check if laravel version Less than 5.4.17
        if (version_compare($this->laravel->version(), '5.4.17', '<')) {
            return $this->laravel->basePath().DIRECTORY_SEPARATOR.'.env';
        }
        return $this->laravel->basePath('.env');
    }
}