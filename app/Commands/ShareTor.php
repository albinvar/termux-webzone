<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ShareTor extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'share:tor';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'portforward through tor';

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
    	if(!file_exists($this->dir.'/tor')){
			if($this->confirm ("Do you want to install tor?")){
				$this->installtor();
				sleep(1);
				$this->call('share:tor');
			} else {
				$this->error('aborting...');
				exit();
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
