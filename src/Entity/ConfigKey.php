<?php
namespace mrgswift\EncryptEnv\Entity;

class ConfigKey
{
    protected $configkey;

    function __construct()
    {
        $configkeyPath = config('encryptenv.config_key_path');

        if (!empty($_SERVER['CONFIGKEY'])) {

            $this->configkey = $_SERVER['CONFIGKEY'];

        } elseif (empty($_SERVER['CONFIGKEY']) && !empty($configkeyPath)) {
            
            $this->configkey = preg_replace('/\s+/', ' ', trim(file_get_contents($configkeyPath)));

        } else {

            $this->configkey = '';

        }
    }
    /**
     * Get Config Encryption Key
     *
     * @return string
     */
    public function get()
    {
        return $this->configkey;
    }
}