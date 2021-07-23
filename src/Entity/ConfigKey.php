<?php
namespace mrgswift\EncryptEnv\Entity;

use Config;

class ConfigKey
{
    protected $configkey;

    function __construct()
    {
        $configkeyPath = Config::get('encryptenv.config_key_path');

        if (!empty($_SERVER['CONFIGKEY'])) {

            $this->configkey = $_SERVER['CONFIGKEY'];

        } elseif (empty($_SERVER['CONFIGKEY']) && !empty($configkeyPath)) {
            
            $this->configkey = preg_replace('/\s+/', ' ', trim($configkeyPath));

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