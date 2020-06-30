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
        $configfile = new ConfigFile;
        $configarr = $configfile->get();

        if (!empty($this->configkey) && count($configfile)) {

            $crypt = new Encrypter($this->configkey, $configfile->getEncEnvConfig()['cipher']);

            return !empty($configarr[$name]) && !is_array($configarr[$name]) && strpos($configarr[$name], "ENC:") === 0 ?
                $crypt->decrypt(substr($configarr[$name], 4)) :
                null;
        }
        return null;
    }
}