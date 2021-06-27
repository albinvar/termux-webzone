<?php

declare(strict_types=1);

namespace App\Commands\Installer;

use Illuminate\Console\Scheduling\Schedule;
use Laminas\Text\Figlet\Figlet;
use LaravelZero\Framework\Commands\Command;

class Symfony extends Command
{
    protected $symfony;

    protected $dir;

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
     */
    public function handle(): mixed
    {
        $this->callSilently('settings:init');
        $this->symfony = config('symfony.PATH');
        $this->dir = '/data/data/com.termux/files/usr/bin';
        $this->install();
    }

    public function install()
    {
        if ($this->checkInstallation()) {
            $this->error('Symfony CLI is already installed. Use "symfony help" to show all commands.');
            return false;
        }

        $this->info(exec('clear'));
        $this->logo();
        $this->comment("\nInstalling Symfony CLI...\n");
        $link = config('symfony.CLI_DOWNLOAD_LINK');
        $lines = shell_exec("curl -w '\n%{http_code}\n' {$link} -o {$this->symfony} && chmod +x {$this->symfony}");
        $lines = explode("\n", trim($lines));
        $status = $lines[count($lines) - 1];
        $this->checkDownloadStatus($status, $this->dir);
    }

    public function checkInstallation()
    {
        if (file_exists($this->symfony)) {
            return true;
        }
        return false;

    
    }

    public function logo(): void
    {
        $figlet = new Figlet();
        $this->comment($figlet->setFont(config('logo.font'))->render('Symfony'));
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    private function checkDownloadStatus($status, $dir): void
    {
        switch ($status) {
            case 000:
                $this->error('Cannot connect to Server');
                break;
            case 200:
                $this->comment("\nDownloaded Successfully...!!!");
                $this->runTasks();
                break;
            case 404:
                $this->error('File not found on server..');
                break;
            default:
                $this->error('An Unknown Error occurred...');
        }
    }

    private function runTasks(): void
    {
        $this->task('verifying command ', function () {
            if (file_exists($this->symfony)) {
                return true;
            }
            return false;

        
        });
    }
}
