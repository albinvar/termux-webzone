<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class MysqlInstaller extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'mysql:install';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Install mysql server on Termux';

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
    	if(file_exists($file)){
	    	$this->error('Mysql seems to be installed already, Type "pkg uninstall mariadb" to uninstall mysql..');
    	} else {
	    	if ($this->confirm('Do you want to install MySql?')) {
		        $this->install();
			  }
	    }
    }
    
    private function install()
    {
    	$cmd = "pkg update -y && pkg upgrade -y && pkg install mariadb -y 2> /dev/null";
	    
		exec($cmd, $output, $result);
		
		$this->comment('MySql installed successfully...');
    }

    /**
     * Define the command's itschedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
