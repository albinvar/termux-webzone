<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Manager extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'manager';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Web based file manager for termux';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$this->fileName = "index.php";
    	$this->link = config('pma.MANAGER_DOWNLOAD_LINK');
	    $this->manager = config('pma.MANAGER_PATH');
        $this->checkInstallation();
    }
    
    private function checkInstallation()
    {
    	if(file_exists($this->manager) && file_exists($this->manager .'/'. $this->fileName))
	    {
			
		} else {
			$this->task("Creating Required Folders", function () {
				exec("mkdir -p {$this->manager}");
			});
			
			$this->task("Creating Required Files", function () {
				
			});
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
