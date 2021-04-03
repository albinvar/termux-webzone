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
    	$this->nodejs = "/data/data/com.termux/files/usr/bin/node";
	    $this->npm = "/data/data/com.termux/files/usr/bin/npm";
        $this->checkInstallation();
    }
    
    public function checkInstallation()
    {
    	if(file_exists($this->nodejs) && file_exists($this->npm)){
	    	
	    } else {
	    	if ($this->confirm('Do you want a present?')) {
		        $this->info("I'll never give you up.");
		    }
		}
		
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
