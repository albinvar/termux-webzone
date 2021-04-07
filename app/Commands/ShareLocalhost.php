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
    
    public function logo()
	{
		 $figlet = new \Laminas\Text\Figlet\Figlet();
		$this->comment($figlet->setFont(config('logo.font'))->render(config('logo.name')));
	}
    
    public function checkInstallation()
    {
    	$this->logo();
    	if(file_exists($this->dir.'/ssh'))
	    {
			$this->activity();
			return true;
		} else {
			if($this->confirm ("Do you want to install openssh?")){
				$this->installopenssh();
				sleep(1);
				$this->call('share:localhost.run');
			} else {
				$this->error('aborting...');
				}
		}
    }
    
    private function activity()
    {
    	exec('ssh -R 80:localhost:8080 localhost.run');
    
    }
    
    private function installopenssh()
    {
	    $this->task("Installing openssh", function () {
			exec('apt-get install openssh -qqq');
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
