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
    protected $signature = 'installer:wordpress
							{--f|--force}';

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
        $this->zip = config('wordpress.WORDPRESS');
        $this->dir = config('wordpress.DIR');
        if ($this->option('force')) {
            $this->removeDir();
        }
        $this->install();
    }
    
    
    public function checkInstallation()
    {
         if (is_dir($this->wordpress) && file_exists($this->wordpress.'/readme.html')) {
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
        $lines = shell_exec("curl -w '\n%{http_code}\n' {$link} -o {$this->zip}");
        $lines = explode("\n", trim($lines));
        $status = $lines[count($lines)-1];
        $this->checkDownloadStatus($status);
    }
    
    
    private function checkDownloadStatus(Int $status)
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
        $this->task("verifying download ", function () {
            if (file_exists($this->zip)) {
                return true;
            } else {
                return false;
            }
        });
        
        $this->task("Extracting WordPress ", function () {
            if ($this->unzip($this->zip)) {
                return true;
            } else {
                return false;
            }
        });
    }
    
    private function unzip()
    {
        $zip = new \ZipArchive();
        $file = $this->zip;
        
        // open archive
        if ($zip->open($file) !== true) {
            return false;
        }
        // extract contents to destination directory
        $zip->extractTo($this->wordpress);
        // close archive
        $zip->close();
        return true;
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
