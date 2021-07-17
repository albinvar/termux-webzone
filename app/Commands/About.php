<?php

declare(strict_types=1);

namespace App\Commands;

use App\Helpers\Webzone;
use Illuminate\Console\Scheduling\Schedule;
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

    public function __construct()
    {
        parent::__construct();
        $this->webzone = new Webzone();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->about();
    }

    public function about(): void
    {
        $this->webzone->logo();
        $this->comment(' ' . app('git.version'));
        $this->newLine();
        $this->info('Termux Webzone is a CLI application which provides a ton of features for web developers to build, run and test their php applications within the limits of android. The application is designed only to work with Termux.');
        $this->newLine();
        $this->comment('  github: https://github.com/albinvar/termux-webzone');
        $this->newLine();
        $this->credits();
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
