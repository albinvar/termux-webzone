<?php

namespace App\Commands\Create;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Laravel extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'create:laravel';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create laravel projects';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->checkComposer();
    }
    
    private function create()
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
