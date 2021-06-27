<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Laminas\Text\Figlet\Figlet;
use LaravelZero\Framework\Commands\Command;

class About extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'about';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'About Webzone';

    /**
     * Execute the console command.
     */
    public function handle(): mixed
    {
        $this->about();
    }

    public function about(): void
    {
        $this->logo();
        $this->comment(' ' . app('git.version'));
        $this->newLine();
        $this->info('Termux Webzone is a CLI application which provides a ton of features for web developers to build, run and test their php applications within the limits of android. The application is designed only to work with Termux.');
        $this->newLine();
        $this->comment('  github: https://github.com/albinvar/termux-webzone');
        $this->newLine();
        $this->credits();
    }

    public function logo(): void
    {
        $figlet = new Figlet();
        echo $figlet->setFont(config('logo.font'))->render(config('logo.name'));
    }

    public function credits(): void
    {
        $headers = ['Developers', 'role'];

        $data = [
            ['Albin', 'Developer'],
        ];

        $this->table($headers, $data);
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
