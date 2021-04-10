<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class LaravelInstaller extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'installer:laravel
							{--uninstall}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Install laravel Installer';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->laravelInstaller = config('pma.LARAVEL_INSTALLER_PATH');

        if ($this->option('uninstall')) {
            $this->uninstall();
        } else {
            $this->install();
        }
    }
    
    public function checkInstallation()
    {
        if (file_exists($this->laravelInstaller)) {
            return true;
        } else {
            return false;
        }
    }
    
    private function install()
    {
        if ($this->checkInstallation()) {
            $this->error('Laravel Installer is already installed. Use "laravel new <app_name>" to create a laravel project.');
            return false;
        }
    
        $this->info("");
        $this->logo();
        $this->comment("\nInstalling Laravel Installer...\n");
        $cmd = exec('composer global require laravel/installer');
        $this->comment("\nInstalled successfully. Launch it using \"laravel --help\" command.\n");
        $this->initComposerGlobal();
    }
    
    private function uninstall()
    {
        if (!$this->checkInstallation()) {
            $this->error('Laravel Installer is not installed yet.');
            return false;
        }
        
        if (!$this->confirm('Do you want to uninstall Laravel Installer?')) {
            return false;
        }
        
        $this->info("");
        $this->logo();
        $this->comment("\nUnnstalling Laravel Installer...\n");
        $cmd = exec('composer global remove laravel/installer');
        $this->comment("\nUninstalled successfully. \n");
    }
    
    private function initComposerGlobal()
    {
        $this->task("Initialize Command ", function () {
            $this->callSilently('composer:global', ['-s' => true]);
        });
    }
    
    public function logo()
    {
        $figlet = new \Laminas\Text\Figlet\Figlet();
        echo $figlet->setFont(config('logo.font'))->render("Laravel  Installer");
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
