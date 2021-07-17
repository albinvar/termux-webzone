<?php

declare(strict_types=1);

namespace App\Helpers;

use Laminas\Text\Figlet\Figlet;
use LaravelZero\Framework\Commands\Command;

class Webzone extends Command
{
    public function __construct()
    {
        $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();
    }

    public function logo($name = null, $type = null, $font = null): void
    {
        $name = is_null($name) ? config('logo.name') : $name;
        $font = is_null($font) ? config('logo.font') : $font;

        $figlet = new Figlet();
        $logo = $figlet->setFont($font)->render($name);

        switch ($type) {
            case 'info':
                $this->info($logo);
                break;

            case 'error':
                $this->error($logo);
                break;

            case 'comment':
                $this->comment($logo);
                break;

            case 'question':
                $this->question($logo);
                break;

            default:
                $this->line($logo);
                break;
        }
    }

    public function clear(): mixed
    {
        return system('clear');
    }
}
