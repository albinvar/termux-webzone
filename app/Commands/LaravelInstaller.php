<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class LaravelInstaller extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'installer:laravel';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Install laravel Installer';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
