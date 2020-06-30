<?php
namespace mrgswift\EncryptEnv\Action;

use mrgswift\EncryptEnv\Entity\ConfigFile;
use mrgswift\EncryptEnv\Entity\ConfigKey;
use Illuminate\Encryption\Encrypter;

class Decrypt
{
    protected $configkey;

    function __construct()
    {
        $configkey = (new ConfigKey)->get();

        !empty($configkey) && $this->configkey = $configkey;
    }

    /**
     * Get Decrypted Config Value
     *
     * @param $name string
     *
     * @return string
     */
    public function get($name)
    {
        $configfile = (new ConfigFile)->get();

        if (!empty($this->configkey) && count($configfile)) {

            $encenv_config = ['cipher' => ''];
            if (file_exists(config_path('encryptenv.php'))) {
                $encenv_config = require(config_path('encryptenv.php'));
            }

            $crypt = new Encrypter($this->configkey, $encenv_config['cipher']);

            return !empty($configfile[$name]) && !is_array($configfile[$name]) && strpos($configfile[$name], "ENC:") === 0 ?
                $crypt->decrypt(substr($configfile[$name], 4)) :
                    null;
        }
        return null;
    }
}