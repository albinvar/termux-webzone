<?php

namespace App\Commands\Installer;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Symfony extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'installer:symfony';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Install Symfony Installer';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->callSilently('settings:init');
        $this->dir = "/data/data/com.termux/files/usr/bin";
        $this->checkInstallation();
    }
    
    public function checkInstallation()
    {
        $link = config('symfony.CLI_DOWNLOAD_LINK');
        $lines = shell_exec("curl -w '\n%{http_code}\n' {$link} -o {$this->dir}/symfony && chmod +x {$this->dir}/symfony");
        $lines = explode("\n", trim($lines));
        $status = $lines[count($lines)-1];
        $this->checkDownloadStatus($status, $this->dir);
    }
    
    private function checkDownloadStatus(Int $status, $dir)
    {
        switch ($status) {
  case 000:
    $this->error("Cannot connect to Server");
    break;
  case 200:
    $this->comment("\nDownloaded Successfully...!!!");
    $this->runTasks();
    break;
  case 404:
    $this->error("File not found on server..");
    break;
  default:
    $this->error("An Unknown Error occurred...");
}
    }
    
    private function runTasks()
    {
        $this->task("verifying command ", function () {
            if (file_exists($this->dir.'/symfony')) {
                return true;
            } else {
                return false;
            }
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
