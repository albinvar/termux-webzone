<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PMA API Server (Do Not Change)
    |--------------------------------------------------------------------------
    |
    | Get latest version of PMA link.
    |
    */

    'PMA_URL' => 'https://albinvar.github.io/webzone-api/v1/config.json',
    
    /*
    |--------------------------------------------------------------------------
    | PMA Installation Directory 
    |--------------------------------------------------------------------------
    |
    | Specify storage location to install PhpMyAdmin files.
    |
    */
    
    'PMA_DIR' => '/data/data/com.termux/files/usr/var/www',
    
    /*
    |--------------------------------------------------------------------------
    | PMA Default Archive 
    |--------------------------------------------------------------------------
    |
    | Set a default PMA archive if the cli fails to contact with API Server
    |
    */
    
    'PMA_DEFAULT_DOWNLOAD_LINK' => 'https://files.phpmyadmin.net/phpMyAdmin/5.1.0/phpMyAdmin-5.1.0-all-languages.zip',

];
