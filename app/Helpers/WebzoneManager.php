<?php

declare(strict_types=1);

namespace App\Helpers;

class WebzoneManager
{
    public function __construct()
    {
        $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();
    }

    public static function getPort()
    {
        return config('manager.PORT', 9876);
    }

    public static function getPath($value = null)
    {
        return $value === 'full' ? config('manager.FULL-PATH', 'manager') : config('manager.PATH', 'manager');
    }

    public static function getLink()
    {
        return config('manager.DOWNLOAD_LINK');
    }

    public static function startServer()
    {
        return exec('cd ' . static::getPath('full') . ' && xdg-open http://127.0.0.1: '. static::getPort() .'/ && php -S 127.0.0.1:'.static::getPort());
    }
}
