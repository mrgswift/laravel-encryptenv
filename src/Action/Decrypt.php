<?php
namespace mrgswift\EncryptEnv\Action;

use mrgswift\EncryptEnv\Entity\ConfigFile;
use mrgswift\EncryptEnv\Entity\ConfigKey;
use Illuminate\Encryption\Encrypter;

class Decrypt
{
    protected $secEnv;

    function __construct()
    {
        $configkey = (new ConfigKey)->get();

        if (!empty($configkey)) {

            $configfile = (new ConfigFile)->get();

            !empty($configkey) && count($configfile) && $crypt = new Encrypter($configkey);

            $secEnv = [];

            foreach ($configfile as $key => $value) {
                //exclude config values that are arrays and test for ENC: prefix in each value
                if (!is_array($value) && strpos($value, "ENC:") === 0) {
                    //Decrypt values with ENC: prefix
                    $secEnv[$key] = $crypt->decrypt(substr($value, 4));
                }
            }

            $this->secEnv = $secEnv;
        }
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
        return isset($this->secEnv[$name]) ? $this->secEnv[$name] : null;
    }
}