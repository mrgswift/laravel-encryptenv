<?php
namespace mrgswift\EncryptEnv\Entity;


class ConfigFile
{
    /**
     * @var array
     */
    protected $configarr;

    /**
     * @var array
     */
    protected $encenv_config;

    /**
     * @var string
     */
    protected $configpath;

    /**
     * @var string
     */
    protected $configoutput;

    /**
     * @var string
     */
    protected $configfile;


    function __construct()
    {
        if (file_exists(config_path('encryptenv.php'))) {
            $encenv_config = require(config_path('encryptenv.php'));
            $this->encenv_config = $encenv_config;
        }
        $this->configfile = !empty($encenv_config['custom_config_file']) ? $encenv_config['custom_config_file'] : null;
        !empty($encenv_config['custom_config_file']) && $this->configpath = config_path($encenv_config['custom_config_file']);
        $this->configarr = !empty($this->configpath) &&
        file_exists($this->configpath) ?
            require $this->configpath :
            (!empty($_ENV) ? $_ENV : getenv());

        !empty($this->configpath) && !empty($encenv_config['custom_config_output']) ?
            $this->configoutput = $encenv_config['custom_config_output'] :
            $this->configoutput = 'env';

        empty($this->configpath) && $this->configpath = base_path('/.env');
    }

    /**
     * Get Config File properties as array
     *
     * @return array
     */
    public function get()
    {
        return $this->configarr;
    }

    /**
     * Get Package Config properties as array
     *
     * @return array
     */
    public function getEncEnvConfig()
    {
        return $this->encenv_config;
    }

    /**
     * Get Config File Path
     *
     * @return string
     */
    public function getConfigPath()
    {
        return $this->configpath;
    }

    /**
     * Get Config Output Format
     *
     * @return string
     */
    public function getOutputFormat()
    {
        return $this->configoutput;
    }

    /**
     * Get Config Filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->configfile;
    }
}