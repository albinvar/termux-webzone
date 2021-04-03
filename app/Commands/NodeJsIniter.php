<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class NodeJsIniter extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'nodejs:init';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'init nodejs and npm';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
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
