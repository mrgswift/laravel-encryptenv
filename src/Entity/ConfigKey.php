<?php
namespace mrgswift\EncryptEnv\Entity;

class ConfigKey
{
    protected $configkey;

    function __construct()
    {

        if (!empty($_SERVER['CONFIGKEY'])) {

            $this->configkey = $_SERVER['CONFIGKEY'];

        } else {
            $configkey = getenv('APP_CONFIGKEY');

            if ($configkey !== false) {

                $this->configkey = $configkey;

            }
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