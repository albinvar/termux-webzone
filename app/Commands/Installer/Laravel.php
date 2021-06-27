<?php

declare(strict_types=1);

namespace App\Commands\Installer;

use Illuminate\Console\Scheduling\Schedule;
use Laminas\Text\Figlet\Figlet;
use LaravelZero\Framework\Commands\Command;

class Laravel extends Command
{
    protected $laravelInstaller;

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
     */
    public function handle(): mixed
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
        }
        return false;

    
    }

    public function logo(): void
    {
        $figlet = new Figlet();
        echo $figlet->setFont(config('logo.font'))->render('Laravel  Installer');
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    private function uninstall()
    {
        if (! $this->checkInstallation()) {
            $this->error('Laravel Installer is not installed yet.');
            return false;
        }

        if (! $this->confirm('Do you want to uninstall Laravel Installer?')) {
            return false;
        }

        $this->info('');
        $this->logo();
        $this->comment("\nUnnstalling Laravel Installer...\n");
        $cmd = exec('composer global remove laravel/installer');
        $this->comment("\nUninstalled successfully. \n");
    }

    private function install()
    {
        if ($this->checkInstallation()) {
            $this->error('Laravel Installer is already installed. Use "laravel new <app_name>" to create a laravel project.');
            return false;
        }

        $this->info('');
        $this->logo();
        $this->comment("\nInstalling Laravel Installer...\n");
        $cmd = exec('composer global require laravel/installer');
        $this->comment("\nInstalled successfully. Launch it using \"laravel --help\" command.\n");
        $this->initComposerGlobal();
    }

    private function initComposerGlobal(): void
    {
        $this->task('Initialize Command ', function (): void {
            $this->callSilently('composer:global', ['-s' => true]);
        });
    }
}
