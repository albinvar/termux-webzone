<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ShareLocalhost extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'share:localhost.run';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'portforward through localhost.run';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$this->dir = "/data/data/com.termux/files/usr/bin";
    	echo exec('clear');
        $this->checkInstallation();
    }
    
    public function checkInstallation()
    {
    	if(file_exists($this->dir.'/ssh'))
	    {
			return true;
		} else {
			$this->installopenssh();
			$this->call('share:localhost.run');
		}
    }
    
    private function installopenssh()
    {
	    $this->task("Installing openssh", function () {
			exec('apt-get install openssh -qqq');
			sleep(1);
			return true;
		});
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
