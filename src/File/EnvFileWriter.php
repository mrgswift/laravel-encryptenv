<?php
namespace mrgswift\EncryptEnv\File;

use Illuminate\Encryption\Encrypter;
use mrgswift\EncryptEnv\Entity\EncryptPayload;

class EnvFileWriter
{
    /**
     * @var \mrgswift\EncryptEnv\Entity\EncryptPayload
     */
    protected $payload;

    /**
     * EnvFileWriter Constructor
     *
     * @param $payload \mrgswift\EncryptEnv\Entity\EncryptPayload
     */
    public function __construct(EncryptPayload $payload)
    {
        $this->payload = $payload;
    }
    /**
     * Write changes to .env file
     *
     * @return array
     */
    public function writeFile()
    {
        $envfile_path = $this->payload->getConfigFile()->getConfigPath();
        $tmpfile_path = base_path('/.env_tmp');

        $envfile = fopen($envfile_path, 'r');
        $tmpfile = fopen($tmpfile_path, 'w') or exit("Command Failed: Unable to write temp file to document root!");

        $envlinecnt = count(file($envfile_path));

        $encrypter = new Encrypter($this->payload->getConfigKey(), $this->payload->getCipher());

        $linenum = 0;
        while (!feof($envfile)) {
            $linenum++;
            $lbreak = $linenum <= $envlinecnt ? "\n" : "";
            $envline = trim(fgets($envfile));

            if (!empty($envline)) {
                $env_val = $envline;

                $chkstrpos = strpos($envline, $this->payload->getFlag());
                //Replace value with encrypted value if '!ENC: flag is found on line
                if ($chkstrpos !== false) {
                    //Get env variable name from line
                    $varname = substr($envline, 0, ($chkstrpos - 1));
                    //Get existing value from line
                    $fileval = substr($envline, strlen($this->payload->getFlag()));
                    //Encrypt existing value
                    $env_val = $varname . '=ENC:' . $encrypter->encrypt($fileval);
                }
                //Write line to tmp file
                fputs($tmpfile, $env_val . $lbreak);

            } else {
                //Write blank line to tmp file (preserves blank lines in .env file)
                $linenum <= $envlinecnt && fputs($tmpfile, $lbreak);
            }
        }
        fclose($envfile);
        fclose($tmpfile);

        //Check to make sure tmp file actually has something in it before replacing .env file
        if (count(file($tmpfile_path))) {

            //Just in case something unexpected happens
            copy($envfile_path, base_path('/.env_bak'));

            //Replace existing .env file with new one
            if (unlink($envfile_path) && rename($tmpfile_path, $envfile_path)) {
                if (file_exists(base_path('/.env_bak'))) {
                    unlink(base_path('/.env_bak'));
                }
                return ['result' => true];

            } else {

                //Attempt to restore old .env file if it gets lost
                if (!file_exists($envfile_path) && file_exists(base_path('/.env_bak'))) {
                    copy(base_path('/.env_bak'), $envfile_path);
                }

                return ['result' => false, 'error' => 'Command Failed: Nothing was changed in your file. The command failed to write to the file ' . $envfile_path];
            }
        } else {

            if (file_exists($tmpfile_path)) {
                unlink($tmpfile_path);
            }

            return ['result' => false, 'error' => 'Command Failed: Nothing was changed in your file. The command failed to write to temp file and/or produce output for new the new config/env file.'];
        }
    }
}