<?php

namespace App\Commands\Installer;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Wordpress extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'installer:wordpress';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Install wordpress Locally';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->callSilently('settings:init');
        $this->wordpress = config('wordpress.PATH');
        $this->dir = config('wordpress.PATH') . '/wordpress';
        $this->install();
    }
    
    
    public function checkInstallation()
    {
        if (file_exists($this->dir)) {
            return true;
        } else {
            return false;
        }
    }
    
    
    public function install()
    {
        if ($this->checkInstallation()) {
            $this->error('Wordpress is already installed. Use "server:wordpress" to start wordpress server.');
            return false;
        }
        
        $this->info(exec('clear'));
        $this->logo();
        $this->comment("\nInstalling Wordpress...\n");
        $link = config('wordpress.DOWNLOAD_LINK');
        $lines = shell_exec("curl -w '\n%{http_code}\n' {$link} -o {$this->wordpress}/wordpress.zip && chmod +x {$this->wordpress}");
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
            if (file_exists($this->dir)) {
                return true;
            } else {
                return false;
            }
        });
    }
    
    public function logo()
    {
        $figlet = new \Laminas\Text\Figlet\Figlet();
        $this->comment($figlet->setFont(config('logo.font'))->render("WordPress"));
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
