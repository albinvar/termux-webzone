<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Illuminate\Support\Facades\Artisan;

class Mysql extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'server:mysql
							{--n}
							{--port=3306}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Start MySql Services';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->checkInstallation();
    }
    
    public function checkInstallation()
    {
    	$file = "/data/data/com.termux/files/usr/bin/mysql";
    	if(!file_exists($file)){
	    	$source = $this->choice(
        "mysql doesn't seem to be installed, do you want to install it now ?",
        [1 => 'install now', 0 => 'cancel']
		    );
		
		if($source == 'install now' || $source == 1) {
			$this->call('install:mysql');
			}
		if($source == 'cancel' || $source == 0) {
			$this->info("Good bye");
			}
    	} else {
	    	$this->start();
	    }
    }
    
    
    private function start()
    {
    	$cmd = shell_exec("mysqld --port={$this->option('port')}");
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
