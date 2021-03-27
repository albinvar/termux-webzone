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

    'PMA_URL' => 'http://localhost:8888/api.php',
    
    /*
    |--------------------------------------------------------------------------
    | PMA Installation Directory 
    |--------------------------------------------------------------------------
    |
    | Specify storage location to install PhpMyAdmin files.
    |
    */
    
    'PMA_DIR' => '/storage/emulated/0/laravel-zero/termux-pma-installer/test',
    
    /*
    |--------------------------------------------------------------------------
    | PMA Default Archive 
    |--------------------------------------------------------------------------
    |
    | Set a default PMA archive if the cli fails to contact with API Server
    |
    */
    
    'PMA_DEFAULT_DOWNLOAD_LINK' => 'http://localhost:8889/phpMyAdmin.zip',

];
