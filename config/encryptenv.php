<?php

/*
 * This file is part of the Encryptenv package for Laravel.
 *
 * (c) Matthew Guillot <mrgswift@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Encryption Cipher
    |--------------------------------------------------------------------------
    |
    | This package uses Laravel's built-in Encryption API
    | Laravel Encrypter supports either AES-128-CBC or AES-256-CBC as a cipher.
    |
    | If you are concerned about the performance and scalability of your
    | application, AES-128-CBC should be more then sufficient to protect your
    | environment variables.  If you are more paranoid, you can use AES-256-CBC
    |
    | More on this here:
    | https://blog.1password.com/guess-why-were-moving-to-256-bit-aes-keys/
    |
    | Change 'cipher' below to AES-128-CBC OR AES-256-CBC to encrypt your environment
    | variables in your .env or custom config file (set custom config below)
    |
    */

    'cipher' => 'AES-128-CBC',

    /*
    |--------------------------------------------------------------------------
    | Encrypt Value Flag
    |--------------------------------------------------------------------------
    |
    | This will tell the EncryptEnv console command what flag to look for at the
    | beginning of each environment variable value, to trigger encrypting the value.
    |
    | For best results, use a string that has little probability of being inside of
    | an actual variable value. Though this package does only check the beginning of
    | each variable value, it is still possible to mistakenly choose an encrypt_flag
    | that is contained at the beginning of an actual variable value. If you make
    | this mistake, this package will partially encrypt the variable value causing
    | unexpected results and most likely making the variable unreadable
    |
    | The default included encrypt_flag should suffice for most setups
    |
    | NOTE:  This cannot be 'ENC:' but you can put anything else here
    |
    */

    'encrypt_flag' => '!ENC:',


    /*
    |--------------------------------------------------------------------------
    | Custom Config File
    |--------------------------------------------------------------------------
    |
    | Set this if you would rather use your own config file and not docroot/.env
    | Otherwise leave this blank.  Any custom config file must be located in
    | the laravel's default config path, which is docroot/config for most Laravel
    | environments. Laravel has the helper function config_path() to return this
    | path if you are unsure what this path is.
    |
    | NOTE: If you set this, your .env file will be completely ignored by this
    | package
    }
    */

    'custom_config_file' => '',

    /*
    |--------------------------------------------------------------------------
    | Custom Config File Output Format
    |--------------------------------------------------------------------------
    |
    | This is only applied if custom_config_file (above) is set to a non-blank
    | value/filename. Valid output formats are 'env' OR 'array'.  Setting to
    | 'env' outputs variables in valid .env file syntax, while 'array' outputs
    | an array usable by Laravel's Config helper class
    |
    */

    'custom_config_output' => 'env'
];
