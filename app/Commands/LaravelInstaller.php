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
    	$this->laravelInstaller = "/data/data/com.termux/files/home/.composer/vendor/bin/laravel";
        $this->checkInstallation();
    }
    
    public function checkInstallation()
    {
    	if(file_exists($this->laravelInstaller)){
	    	$this->error('Laravel Installer is already installed. Use "laravel new <app_name>" to create a laravel project.');
		} else {
			$this->install();
		}
    }
    
    private function install()
    {
    	$this->info("");
    	$this->logo();
	    $this->comment("\nInstalling Laravel Installer...\n");
    	$cmd = exec('composer global require laravel/installer'); 
	    $this->comment("\nInstalled successfully. Launch it using \"laravel --help\" command.\n");
    }
    
    public function logo()
	{
		 $figlet = new \Laminas\Text\Figlet\Figlet();
		echo $figlet->setFont(config('logo.font'))->render("Laravel  Installer");
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
