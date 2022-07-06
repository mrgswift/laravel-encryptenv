<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use mrgswift\EncryptEnv\Action\Encrypt;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class SecEnvConsoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'encryptenv:console {console_command} {configkey?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs a console command using the user-provided CONFIGKEY';

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

        while (empty($configkey)) {
            $configkey = $this->ask('Config Key ('.$encrypter->getKeySize().' char key)');
        }

        $cmdarr = explode(' ', $this->argument('console_command'));

        $process = new Process($cmdarr, null,[
            'APP_CONFIGKEY' => $configkey
        ]);

        unset($configkey);

        $process->run();

        echo $process->getOutput();

        unset($process);

        return true;
    }
}