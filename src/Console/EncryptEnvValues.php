<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use mrgswift\EncryptEnv\Action\Encrypt;
use Illuminate\Encryption\Encrypter;

class EncryptEnvValues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'encryptenv:encrypt {configkey?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encrypts flagged environment/config variable values in file defined in encryptenv.php';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return boolean
     */
    public function handle()
    {
        $encrypter = new Encrypt;

        $configkey = $this->argument('configkey');
        $configkey = !empty($configkey) ? $configkey : $this->ask('Config Key ('.$encrypter->getKeySize().' char key)');

        $generated_key = false;

        if ($configkey === 'generate-key') {

            $cipher = $encrypter->getKeySize() === 16  ? 'AES-128-CBC' : ($encrypter->getKeySize() === 32 ? 'AES-256-CBC' : null);

            if (!empty($cipher)) {

                $configkey = substr(str_replace('/', '', base64_encode(Encrypter::generateKey($cipher))),0, $encrypter->getKeySize());

                if (!empty($configkey)) {

                    $generated_key = true;

                } else {

                    $this->error('Command Failed: An unknown problem occurred trying to generate a new config key!');
                    return false;

                }

            } else {

                $this->error('Command Failed:  An encryption cipher is either not defined in '.config_path('encryptenv.php').' or the cipher specified is not supported.');
                return false;

            }
        }

        $encrypter->setConfigKey($configkey);
        $doencrypt = $encrypter->encryptenvfile();

        if (!$doencrypt['result']) {

            $this->error($doencrypt['error']);
            return false;

        } else {
            $this->info('Done!');

            if ($generated_key) {
                $this->info('');
                $this->info('Your new generated CONFIGKEY is: '.$configkey."\n");
                $this->warn('DO NOT lose this key if you want to use the encrypted config values you just encrypted.');
                $this->warn('You will need to update your web service configuration file with this new CONFIGKEY');
                $this->info('Refer to the Install [Configure your web service] section in the README for more info');
            }

            return true;
        }
    }
}
