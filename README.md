<h1 align="center"> Termux Webzone </h1> 
<p align="center">
    <img title="Laravel Zero" height="100" src="https://i.ibb.co/kVTNLCZ/68747470733a2f2f692e696d6775722e636f6d2f384975594c526c2e6a7067.jpg">
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
- [Features](#features)
- [Screenshots](#screenshots)
- [Installation](#installation)
- [Contributing](#contributing)
- [License](#license)

## Introduction

Termux Webzone is a cli application which provides a ton of features for web developers to build, run and test their php applications within the limits of android.
The application is designed only to work with <a href="https://play.google.com/store/apps/details?id=com.termux" target="_blank">Termux</a>.

## Installation

### Using wget
Run the command and the script will take care of the rest.
```bash
wget https://raw.githubusercontent.com/albinvar/webzone-api/main/installer/installer.php -O - | bash
```

### Using composer
```php
composer global require albinvar/termux-webzone 
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
