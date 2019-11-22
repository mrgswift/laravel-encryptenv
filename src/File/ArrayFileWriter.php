<?php
namespace mrgswift\EncryptEnv\File;

use Illuminate\Encryption\Encrypter;
use mrgswift\EncryptEnv\Entity\EncryptPayload;

class ArrayFileWriter
{
    /**
     * @var \mrgswift\EncryptEnv\Entity\EncryptPayload
     */
    protected $payload;

    /**
     * @var array
     */
    protected $data;

    /**
     * ArrayFileWriter Contructor
     *
     * @param $payload \mrgswift\EncryptEnv\Entity\EncryptPayload
     */
    public function __construct(EncryptPayload $payload)
    {
        $this->payload = $payload;
    }

    private function getFileDocBlock()
    {
        $docComments = array_filter(
            token_get_all( file_get_contents( $this->payload->getConfigFile()->getConfigPath() ) ), function($entry) {
            return $entry[0] == T_DOC_COMMENT;
        }
        );

        $fileDocComment = array_shift( $docComments );

        return !empty($fileDocComment[1]) ? $fileDocComment[1] : null;
    }

    private function set_deep_index_value($data_indexes, $new_value)
    {
        foreach ($data_indexes as $index){
            if($index == sizeof($data_indexes)-1)  $this->data[$index]=$new_value;
            else return $this->set_deep_index_value(array_slice($data_indexes,1),$new_value);
        }
    }

    private function update_config($target, $new_value = null)
    {

        if (is_array($target) && sizeof($target) == 1){
            $new_value = array_values($target)[0];
            $target = array_keys($target)[0];
        }

        $array = explode('.',$target);
        $filepath = $this->payload->getConfigFile()->getConfigPath();
        $data_indexes = array_slice($array,1);

        $this->data = require($filepath);

        $this->set_deep_index_value($data_indexes, $new_value);

        $docblockpull = $this->getFileDocBlock();
        $docblock = !empty($docblockpull) ? $docblockpull : '';
        file_put_contents($filepath,"<?php\n".$docblock."\n\n return ".var_export($this->data,1)." ;");

    }
    /**
     * Write changes to config file
     *
     * @return array
     */
    public function writeFile()
    {
        $configpath = $this->payload->getConfigFile()->getConfigPath();
        $filename = $this->payload->getConfigFile()->getFilename();
        $envfile = require($configpath);

        if (!is_array($envfile)) {
            return ['result' => false, 'error' => 'Command Failed:  Config File Output Format is set as \'array\' but file contents in '.$configpath.' is not formatted as an array.'];
        }

        $encrypter = new Encrypter($this->payload->getConfigKey(), $this->payload->getCipher());

        foreach ($envfile as $key => $val) {

            if (!is_array($val) && strpos($val, $this->payload->getFlag()) !== false) {

                $encval = substr($val, strlen($this->payload->getFlag()));
                $target = str_replace('.php','',$filename).'.'.$key;

                $this->update_config($target, 'ENC:' . $encrypter->encrypt($encval));
            }
        }
        return ['result' => true];
    }
}