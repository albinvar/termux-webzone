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
	    $this->bashrc = "/storage/emulated/0/laravel-zero/termux-pma-installer/test/bash.bashrc";
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
        
        $this->checkIfInitialized();
        
    }
    
    private function checkIfInitialized()
    {
    	$file = file_get_contents($this->bashrc);
		$string = 'PATH=$PATH:/data/data/com.termux/files/home/.composer/vendor/bin';
		if( strpos(file_get_contents($this->bashrc), $string) !== false) {
	        $this->comment("\nComposer has already been initiated globally");
	    }else{
			$this->rewrite();
		}
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
