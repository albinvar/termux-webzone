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
    | MySql Command
    |--------------------------------------------------------------------------
    |
    | Mysql command
    |
    */
     'MYSQL_PATH' => '/data/data/com.termux/files/usr/bin/mysql',
    
    /*
    |--------------------------------------------------------------------------
    | MySql Installation Command
    |--------------------------------------------------------------------------
    |
    | Set base path for development server.
    |
    */
     'MYSQL_INSTALLATION_COMMAND' => 'pkg update -y && pkg upgrade -y && pkg install mariadb -y 2> /dev/null',
    
    /*
    |--------------------------------------------------------------------------
    | PATH Environment Variable
    |--------------------------------------------------------------------------
    |
    | used on bash.bashrc
    |
    */
     'path' => 'PATH=\$PATH:/data/data/com.termux/files/home/.composer/vendor/bin',
    
    /*
    |--------------------------------------------------------------------------
    | PROJECT Base Path
    |--------------------------------------------------------------------------
    |
    | Set base path for development server.
    |
    */
     'PROJECT_BASE_PATH' => '/sdcard/www',
    
    /*
    |--------------------------------------------------------------------------
    | LARAVEL INSTALLER Path
    |--------------------------------------------------------------------------
    |
    | Set laravel installer path.
    |
    */
     'LARAVEL_INSTALLER_PATH' => '/data/data/com.termux/files/home/.composer/vendor/bin/laravel',
    
    /*
    |--------------------------------------------------------------------------
    | Ngrok Download link
    |--------------------------------------------------------------------------
    |
    | Set ngrok download link.
    |
    */
     'NGROK' => 'https://gitlab.com/albinvar/pma-cli/-/raw/master/ngrok?inline=false',
    
    /*
    |--------------------------------------------------------------------------
    | Torrc
    |--------------------------------------------------------------------------
    |
    | Set torrc path.
    |
    */
     'TORRC' => '/data/data/com.termux/files/usr/etc/tor/torrc',
    
    /*
    |--------------------------------------------------------------------------
    | Torrc Download link
    |--------------------------------------------------------------------------
    |
    | Set torrc download link.
    |
    */
     'TORRC_DOWNLOAD_LINK' => 'https://gitlab.com/albinvar/pma-cli/-/raw/master/torrc?inline=false',
    
    /*
    |--------------------------------------------------------------------------
    | Torrc Hidden Folder
    |--------------------------------------------------------------------------
    |
    | Stores public key and private key for your hostname.
    |
    */
     'TOR_HIDDEN_DIR' => '/data/data/com.termux/files/usr/var/lib/tor/hidden_service',
    
    /*
    |--------------------------------------------------------------------------
    | PHP-CS-FIXER Path
    |--------------------------------------------------------------------------
    |
    | Set php-cs-fixer installer path.
    |
    */
     'PHP_CS_FIXER_PATH' => '/data/data/com.termux/files/home/.composer/vendor/bin/php-cs-fixer',
    
    /*
    |--------------------------------------------------------------------------
    | File Manager Download Link
    |--------------------------------------------------------------------------
    |
    | Set file manager php Download link.
    |
    */
     'MANAGER_DOWNLOAD_LINK' => 'https://gitlab.com/albinvar/pma-cli/-/raw/master/fm.php?inline=false',
    
    /*
    |--------------------------------------------------------------------------
    | File Manager Download Link
    |--------------------------------------------------------------------------
    |
    | Set file manager php Download link.
    |
    */
     'MANAGER_PATH' => '/data/data/com.termux/files/usr/var/webzone/manager',
    
    /*
    |--------------------------------------------------------------------------
    | PHPSTAN Path
    |--------------------------------------------------------------------------
    |
    | Set phpstan installer path.
    |
    */
     'PHPSTAN_PATH' => '/data/data/com.termux/files/home/.composer/vendor/bin/phpstan',
    
];
