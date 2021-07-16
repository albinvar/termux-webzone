<?php

namespace App\Helpers;

use LaravelZero\Framework\Commands\Command;
use Laminas\Text\Figlet\Figlet;

class Webzone extends Command
{
    public function __construct()
    {
        $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();
    }

    public function logo($name=null, $type='info', $font=null): void
    {
        $name = (is_null($name)) ? config('logo.name') : $name;
        $font = (is_null($font)) ? config('logo.font') : $font;

        $figlet = new Figlet();
        $logo = $figlet->setFont($font)->render($name);

        switch ($type) {
            case('info'):
                $this->info($logo);
                break;

            case('error'):
                $this->error($logo);
                break;

            case('info'):
                $this->error($logo);
                break;

            default:
                $this->error($logo);
                break;
        }
    }

    public function clear(): mixed
    {
        return system('clear');
    }
}
