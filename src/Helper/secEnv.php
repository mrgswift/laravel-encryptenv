<?php
use mrgswift\EncryptEnv\Action\Decrypt;

if (!function_exists('secEnv')) {

    /**
     * secEnv Helper function
     *
     * @param $name string
     * @param $fallback string
     *
     * @return string
     */
    function secEnv($name, $fallback='') {

        $configval = (new Decrypt)->get($name);

        return isset($configval) ? $configval : $fallback;
    }
}