<h1 align="center"> Termux Webzone </h1> 
<p align="center">
    <img title="Laravel Zero" height="100" src="https://i.ibb.co/PMrx8jJ/d5651c2b-0efb-45fe-81b7-a4d4d97af732.png"><br>
<br>
<img src="https://img.shields.io/packagist/v/albinvar/termux-webzone?label=version">
<img src="https://poser.pugx.org/albinvar/termux-webzone/downloads">
<a href="https://github.com/albinvar/termux-webzone/actions/workflows/php.yml"><img src="https://github.com/albinvar/termux-webzone/actions/workflows/php.yml/badge.svg"></a>
<img src="https://img.shields.io/github/repo-size/albinvar/termux-webzone">
<a href="LICENSE"><img src="https://img.shields.io/apm/l/Github"></a>
</p>

<pre>
 _    _      _       ______                 
| |  | |    | |     |___  /                 
| |  | | ___| |__      / /  ___  _ __   ___ 
| |/\| |/ _ \ '_ \    / /  / _ \| '_ \ / _ \
\  /\  /  __/ |_) | ./ /__| (_) | | | |  __/
 \/  \/ \___|_.__/  \_____/\___/|_| |_|\___|
                                            
                                            
</pre>

## Table of Contents

- [Introduction](#introduction)
- [Installation](#installation)
- [Features](#features)
- [Screenshots](#screenshots)
- [Commands](#commands)
- [Contributing](#contributing)
- [License](#license)

## Introduction

Termux Webzone is a CLI application which provides a ton of features for web developers to build, run and test their php applications within the limits of android.
The application is designed only to work with <a href="https://play.google.com/store/apps/details?id=com.termux" target="_blank">Termux</a>.

## Requirements
- php 7.3+
- curl
- termux

## Installation

### Basics
Basically, we need to update and upgrade our packages first. After that we will install php and setup permission to use storage.

```bash
pkg update -y
pkg upgrade -y
pkg install php wget -y
termux-setup-storage
```

### Using wget
Run the command and the script will take care of the rest. 
```bash
wget https://raw.githubusercontent.com/albinvar/webzone-api/main/installer/installer.php -O - | php
```

## Using curl
Run the command and the script will take care of the rest. 
```bash
curl -s https://raw.githubusercontent.com/albinvar/webzone-api/main/installer/installer.php | php
```

### Using composer
Remember, installing with composer requires each and every libraries should be downloaded first.

```php
composer global require albinvar/termux-webzone 
```

### Manual installation
- Download the script from [here](https://raw.githubusercontent.com/albinvar/webzone-api/main/installer/installer.php).
- Execute the file using php.
```php
php installer.php
```

- You can also install via composer by adding the flag `-c` or `--composer`.
```php
php installer.php --composer
```

## Updation

You can update webzone simply using the inbuilt command.

```bash
webzone self-update
```

Or if you have installed via composer, use

```bash
composer global update albinvar/termux-webzone
```

Additionaly, old users needs to regenerate settings using the command ```webzone settings:init -f``` for any major updates.

## Features

- Install PhpMyAdmin
- Create Wordpress site
- Create Development server
- Configure composer globally
- Create almost 4+ php framework projects.
- Create onion sites (tor)
- Portforwading through Ngrok
- Portforwading through Localhost.run
- Settings option to configure everything.
- More features coming soon...

## Screenshots

|Installer|Webzone CLI|
|--|--|
|![desktop](https://i.ibb.co/7Yv6YfX/IMG-20210330-231901.jpg)|![desktop](https://i.ibb.co/nRVHtgw/IMG-20210330-231922.jpg)|

## Commands

The following commands are available in our tool. You can use the individual crafting routines which are similar to the Artisan commands.

##### basics Commands
-   about
-   manager
-   settings
-   self-update

##### Installable packages
-   install:mysql
-   install:pma

##### Create Commands
-   create:codelighniter
-   create:laravel
-   create:nette
-   create:symfony
-   create:sapper
-   create:zend

##### Installer Commands
-   installer:laravel
-   installer:symfony
-   installer:fixer
-   installer:phpstan
-   installer:wordpress

##### Server Commands
-   server:all
-   server:dev
-   server:pma
-   server:mysql
-   server:wordpress

##### Port-forwarding Commands
-   share:localhost.run
-   share:ngrok
-   share:tor

##### Others
-   composer:global
-   project:list (Beta)

## Available Commands

You can use this Commands specifically builded for each features.

## Basic Commands

### manager
```bash
$ webzone manager 
```

**Use** : An interactive web interface for managing files inside termux storage built using php. Thanks to [Ging-dev](https://github.com/ging-dev) for this attractive feature.

`options`
- `-f | --force` -> reinstall file manager forcefully if you have any errors.

<hr>

### settings
```bash
$ webzone settings
```
**Use** : Helps to configure ports and paths to be used for each commands. 

<hr>

### self-update
```bash
$ webzone self-update
```
**Use** : Automatically updates webzone to latest version.
<hr>

### composer:global
```bash
$ webzone composer:global
```
**Use** : Configure composer globally. 
<hr>

## Create Commands

### create:codelighniter
```bash
$ webzone create:codelighniter blog 
```
**Use** : Create a fresh new codelighniter project on default project root.

`arguments`
- `name` -> Sets App/Project name.

`options`
- `--path` _(optional)_ -> Overides default app/project root to a custom root.
<hr>

### create:laravel
```bash
$ webzone create:laravel blog 
```
**Use** : Create a fresh new laravel project on default project root.

`arguments`
- `name` -> Sets App/Project name.

`options`
- `--path`_(optional)_ -> Overides default app/project root to a custom root.
<hr>

### create:symfony
```bash
$ webzone create:symfony blog --type api
```
**Use** : Create a fresh new Symfony project on default project root.

`arguments`
- `name` -> Sets App/Project name.

`options`
- `--path` _(optional)_ -> Overides default app/project root to a custom root.
- `--type` _(optional)_ -> Sets application type. By default uses `web` as type. Expects `web` or `api`. 
<hr>

### create:zend
```bash
$ webzone create:zend blog 
```
**Use** : Create a fresh new zend project on default project root.

`arguments`
- `name` -> Sets App/Project name.

`options`
- `--path` _(optional)_ -> Overides default app/project root to a custom root.
<hr>

### create:nette
```bash
$ webzone create:nette blog
```
**Use** : Create a fresh new nette project on default project root.

`arguments`
- `name` -> Sets App/Project name.

`options`
- `--path` _(optional)_ -> Overides default app/project root to a custom root.
<hr>

### create:lumen
```bash
$ webzone create:lumen blog
```
**Use** : Create a fresh new lumen project on default project root.

`arguments`
- `name` -> Sets app/project name.

`options`
- `--path` _(optional)_ -> Overides default app/project root to a custom root.
<hr>

### create:cakephp
```bash
$ webzone create:cakephp blog
```
**Use** : Create a fresh new nette project on default project root.

`arguments`
- `name` -> Sets app/project name.

`options`
- `--path` _(optional)_ -> Overides default app/project root to a custom root.

<hr>

### create:yii
```bash
$ webzone create:yii blog --type=basic
```
**Use** : Create a fresh new Yii project on default project root.

`arguments`
- `name` -> Sets app/project name.

`options`
- `--path` _(optional)_ -> Overides default app/project root to a custom root.
- `--type` _(optional)_ -> Sets application type. By default uses `basic` as type. Expects `basic` or `advanced`.  
<hr>

### create:sapper
```bash
$ webzone create:sapper --name blog 
```
**Use** : Create a fresh new sapper project on default project root.


`options`
- `--name` -> Set's sapper project name.
- `--path`_(optional)_ -> Overides default app/project root to a custom root.
<hr>


## Installable packages

### install:mysql
```bash
$ webzone install:mysql
```
**Use** : Install MySql Database (mariadb).

<hr>

### install:pma
```bash
$ webzone install:pma
```
**Use** : Download and Install PhpMyAdmin latest version from server.

`options`
- `-f|--force` ->  Redownload and Reinstall PhpMyAdmin forcefully.
<hr>


## Installer Commands

### installer:fixer
```bash
$ webzone installer:fixer 
```
**Use** : Install and configure php-cs-fixer globally. You can use `php-cs-fixer -h` for more details.

`options`
- `--uninstall` -> Remove php-cs-fixer from device.
<hr>

### installer:laravel
```bash
$ webzone installer:laravel
```
**Use** : Install laravel-installer latest version from packagist.

`options`
- `--uninstall` ->  Remove laravel-installer globally.
<hr>

### installer:phpstan
```bash
$ webzone installer:phpstan
```
**Use** : Install phpstan latest version from packagist.

`options`
- `--uninstall` ->  Remove phpstan globally.
<hr>

### installer:symfony
```bash
$ webzone installer:symfony
```
**Use** : Install Symfony CLI from server.

<hr>

### installer:wordpress
```bash
$ webzone installer:wordpress
```
**Use** : Install wordpress latest version from server.

`options`
- `-f|--force` ->  Reinstall wordpress from server.
<hr>

Server Commands

### server:all
```bash
$ webzone server:all
```
**Use** : Enable Localhost, Pma, Mysql just in one command (BETA).

<hr>

### server:dev
```bash
$ webzone server:dev
```
**Use** : Start localhost for development with the path set in settings. by default uses _/sdcard/www_.

`options`
- `--n` -> Prevent opening browser after starting server.
- `--port` ->  Overides default port (8080) set in settings. 
- `--path` ->  Overides default path (/sdcard/www) set in settings. 
<hr>

### server:mysql
```bash
$ webzone server:mysql
```
**Use** : Start MySql database  with the default port set in settings. by default uses _/sdcard/www_.

`options`
- `--port` ->  Overides default port (3306) set in settings. 
- `-s | --stop` ->  Kill all Mysql processes. 
<hr>

### server:pma
```bash
$ webzone server:pma
```
**Use** : Start PhpMyAdmin on default port (8000) in settings.

`options`
- `--n` -> Prevent opening browser after starting server.
- `--port` ->  Overides default port (8000) set in settings. 
<hr>

### server:wordpress
```bash
$ webzone server:wordpress
```
**Use** : Start wordpress site on default port (7070) in settings.

`options`
- `--n` -> Prevent opening browser after starting server.
- `--port` ->  Overides default port (7070) set in settings. 
<hr>

##### Port-forwarding Commands

### share:localhost.run
```bash
$ webzone share:localhost.run
```
**Use** : Connect a tunnel to your web appplication running on port 8080 (default) 

`options`
- `--port` ->  Overides default port (8080). 
<hr>

### share:localhost.run
```bash
$ webzone share:localhost.run
```
**Use** : Connect a tunnel to your web appplication running on port 8080 (default) 

`options`
- `--port` ->  Overides default port (8080). 
<hr>

### share:localhost.run
```bash
$ webzone share:localhost.run
```
**Use** : Connect a tunnel to your web appplication running on port 8080 (default) 

`options`
- `--port` ->  Overides default port (8080). 
<hr>

### share:ngrok
```bash
$ webzone share:ngrok
```
**Use** : ngrok provides a real-time web UI where you can introspect all HTTP traffic running over your tunnels. Replay any request against your tunnel with one click.

`options`
- `--port` ->  Overides ngrok port setting.
- `-s | --stop` ->  Kill all Ngrok processes. 
<hr>

### share:tor
```bash
$ webzone share:tor
```
**Use** :  Set up a website in the Onion/Tor network.

_(**NB**: The developer of webzone will not be responsible for any misuses done by users.)_ 

`options`
- `--reset` ->  Reset tor configuration.
- `--port` ->  Set the port to be shared to tor Network
<hr>

### project:list (beta)
```bash
$ webzone project:list
```
**Use** :  Show all directories inside project root folder.

<hr>

## Credits

[@albinvar](https://github.com/albinvar)
[@shmshd](https://github.com/shmshd)

## Contributing

## License
MIT. See [LICENSE](LICENSE) for more details.
