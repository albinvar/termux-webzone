<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ResetTorrc extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'tor:reset';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Reset torrc';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$this->torrc = "/storage/emulated/0/laravel-zero/webzone/test/torrc";
        $this->runTasks();
        $this->call('share:tor');
    }
    
    private function runTasks()
    {
    	// Task 1
    	$this->task("Removing old torrc", function () {
		
			if(file_exists($this->torrc))
			{
				unlink($this->torrc);
				return true;
			} else {
				return false;
			}
            
		});
		
		// Task 2
    	$this->task("Downloading torrc from server", function () {
		
			$this->downloadCurl();
		
		});
    }
    
    private function downloadCurl()
    {
    	$lines = shell_exec("curl -w '\n%{http_code}\n' http://localhost:7070/torrc -o {$this->torrc}");
	    $lines = explode("\n", trim($lines));
		$status = $lines[count($lines)-1];
		$this->checkDownloadStatus($status);
    }
    
    
    private function checkDownloadStatus(Int $status)
    {
    	switch ($status) {
  case 000:
    $this->error("Cannot connect to Server");
    return false;
    break;
  case 200:
    $this->comment("\nDownloaded Successfully...!!!");
    return true;
    break;
  case 404:
    $this->error("File not found on server..");
    return false;
    break;
  default:
    $this->error("An Unknown Error occurred...");
    return false;
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
