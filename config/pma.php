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
    
    /*
    |--------------------------------------------------------------------------
    | Composer
    |--------------------------------------------------------------------------
    |
    | Composer installation path.
    |
    */
	 'composer' => "/data/data/com.termux/files/usr/bin/composer",
	
	/*
    |--------------------------------------------------------------------------
    | Bashrc
    |--------------------------------------------------------------------------
    |
    | 'bash.bashrc' path.
    |
    */
	 'bashrc' => "/data/data/com.termux/files/usr/etc/bash.bashrc",
	
	/*
    |--------------------------------------------------------------------------
    | PATH Environment Variable
    |--------------------------------------------------------------------------
    |
    | used on bash.bashrc
    |
    */
	 'path' => 'PATH=\$PATH:/data/data/com.termux/files/home/.composer/vendor/bin',
	
];
