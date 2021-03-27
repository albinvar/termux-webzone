<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Server extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'server:pma';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Start PhpMyAdmin locally';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->checkInstallations();
    }
    
    public function checkInstallations()
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
