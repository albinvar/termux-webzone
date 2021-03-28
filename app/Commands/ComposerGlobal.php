<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ComposerGlobal extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'composer:global';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Make composer commands global';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$this->composer = "/data/data/com.termux/files/usr/bin/composer";
        $this->checkInstallation();
    }
    
    private function checkInstallation()
    {
    	$this->logo();
	    $this->info("\n");
	    $is_installed = $this->task("Check whether composer is installed ", function () {
     	
            if(file_exists($this->composer))
            { return true; }
            else
			{ return false; }
        });
        
        
        
    }
    
    public function logo()
	{
		 $figlet = new \Laminas\Text\Figlet\Figlet();
		echo $figlet->setFont(config('logo.font'))->render(config('logo.name'));
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
