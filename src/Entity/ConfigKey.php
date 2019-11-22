<?php
namespace mrgswift\EncryptEnv\Entity;

class ConfigKey
{
    protected $configkey;

    function __construct()
    {
        if (!empty($_SERVER['CONFIGKEY'])) {

            $this->configkey = $_SERVER['CONFIGKEY'];

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