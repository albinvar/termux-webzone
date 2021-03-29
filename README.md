<h1 align="center"> Termux Webzone </h1> 
<p align="center">
    <img title="Laravel Zero" height="100" src="https://i.ibb.co/PMrx8jJ/d5651c2b-0efb-45fe-81b7-a4d4d97af732.png"><br>
<br>
<img src="https://img.shields.io/packagist/v/albinvar/termux-webzone?label=version">
<img src="https://img.shields.io/packagist/dm/albinvar/termux-webzone">
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

## Installation

### Using wget
Run the command and the script will take care of the rest. 
```bash
wget https://raw.githubusercontent.com/albinvar/webzone-api/main/installer/installer.php -O - | php
```

### Using composer
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

## Features

- Install Mysql
- Install PhpMyAdmin
- Create Development server
- Configure composer globally
- Install Laravel Installer
- More features coming soon...

## Screenshots

|Default Interface|After Uploading|
|--|--|
|![desktop]()|![desktop]()|

## Commands

The following commands are available in our tool. You can use the individual crafting routines which are similar to the Artisan commands.

-   install:mysql
-   install:pma
-   installer:laravel
-   installer:symfony
-   server:dev
-   server:pma
-   server:mysql

## Contributing

## License
MIT. See [LICENSE](LICENSE) for more details.
