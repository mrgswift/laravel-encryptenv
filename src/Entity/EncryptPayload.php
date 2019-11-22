<?php
namespace mrgswift\EncryptEnv\Entity;

class EncryptPayload
{
    /**
     * @var string
     */
    protected $configkey;

    /**
     * @var string
     */
    protected $cipher;

    /**
     * @var string
     */
    protected $flag;

    /**
     * @var string
     */
    protected $configfile;

    /**
     * EncryptPayload Contructor
     *
     * @param $configkey string
     * @param $cipher string
     * @param $flag string
     * @param $configfile \mrgswift\EncryptEnv\Entity\ConfigFile
     */
    public function __construct($configkey, $cipher, $flag, ConfigFile $configfile)
    {
        $this->configkey = $configkey;
        $this->cipher = $cipher;
        $this->flag = $flag;
        $this->configfile = $configfile;
    }

    /**
     * Get Config Encryption Key
     *
     * @return string
     */
    public function getConfigKey()
    {
        return $this->configkey;
    }

    /**
     * Get Encryption Cipher
     *
     * @return string
     */
    public function getCipher()
    {
        return $this->cipher;
    }

    /**
     * Get Config File Encrypt Flag
     *
     * @return string
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Get Config Filename
     *
     * @return string
     */
    public function getConfigFile()
    {
        return $this->configfile;
    }
}