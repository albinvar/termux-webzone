<?php

declare(strict_types=1);

namespace App\Commands\Share;

use Illuminate\Console\Scheduling\Schedule;
use Laminas\Text\Figlet\Figlet;
use LaravelZero\Framework\Commands\Command;

class LocalhostRun extends Command
{
    protected $dir;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'share:localhost.run';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'portforward through localhost.run';

    /**
     * Execute the console command.
     */
    public function handle(): mixed
    {
        $this->dir = '/data/data/com.termux/files/usr/bin';
        echo exec('clear');
        $this->checkInstallation();
    }

    public function checkInstallation()
    {
        $this->logo();
        if (file_exists($this->dir . '/ssh')) {
            $this->activity();
            return true;
        }
        if ($this->confirm('Do you want to install openssh?')) {
            $this->installopenssh();
            sleep(1);
            $this->call('share:localhost.run');
        } else {
            $this->error('aborting...');
        }

    
    }

    public function logo(): void
    {
        $figlet = new Figlet();
        $this->comment($figlet->setFont(config('logo.font'))->render(config('logo.name')));
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    private function activity(): void
    {
        exec('ssh -R 80:localhost:8080 localhost.run');
    }

    private function installopenssh(): void
    {
        $this->task('Installing openssh', function () {
            exec('apt-get install openssh -qqq');
            return true;
        });
    }
}
