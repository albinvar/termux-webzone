<?php

namespace App\Commands\Installer;

use Illuminate\Console\Scheduling\Schedule;
use Laminas\Text\Figlet\Figlet;
use LaravelZero\Framework\Commands\Command;

class Phpstan extends Command
{
    protected $phpstan;


    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'installer:phpstan
							{--uninstall}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Install phpstan globally';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->phpstan = config('pma.PHPSTAN_PATH');

        if ($this->option('uninstall')) {
            $this->uninstall();
        } else {
            $this->install();
        }
    }

    private function uninstall()
    {
        if (!$this->checkInstallation()) {
            $this->error("phpstan isn't installed yet.");
            return false;
        }

        if (!$this->confirm('Do you want to uninstall phpstan?')) {
            return false;
        }

        $this->info("");
        $this->logo();
        $this->comment("\nUnnstalling phpstan ...\n");
        $cmd = exec('composer global remove phpstan/phpstan');
        $this->comment("\nUninstalled successfully. \n");
    }

    public function checkInstallation()
    {
        if (file_exists($this->phpstan)) {
            return true;
        } else {
            return false;
        }
    }

    public function logo()
    {
        $figlet = new Figlet();
        $this->info($figlet->render("PHPSTAN"));
    }

    private function install()
    {
        if ($this->checkInstallation()) {
            $this->error('Phpstan is already installed. Use "phpstan analyse --level=1 -- <folder_name>" to list out errors in code.');
            return false;
        }
        $this->info(exec("clear"));
        $this->info("");
        $this->logo();
        $this->comment("\nInstalling phpstan...\n");
        $cmd = exec('composer global require phpstan/phpstan');
        $this->comment("\nInstalled successfully. Launch it using \"phpstan --help\" command.\n");
        $this->initComposerGlobal();
    }

    private function initComposerGlobal()
    {
        $this->task("Initialize Command ", function () {
            $this->callSilently('composer:global', ['-s' => true]);
        });
    }

    /**
     * Define the command's schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
