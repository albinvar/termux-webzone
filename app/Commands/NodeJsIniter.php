<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Laminas\Text\Figlet\Figlet;
use LaravelZero\Framework\Commands\Command;

class NodeJsIniter extends Command
{
    protected $nodeJs;

    protected $npm;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'nodejs:init';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'init nodejs and npm';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->nodeJs = '/data/data/com.termux/files/usr/bin/node';
        $this->npm = '/data/data/com.termux/files/usr/bin/npm';
        $this->checkInstallation();
    }

    public function checkInstallation(): void
    {
        $nodejs = $this->check($this->nodeJs, 'Checking Nodejs ');
        $npm = $this->check($this->npm, 'Checking Npm ');

        if (! $nodejs && ! $npm) {
            if ($this->confirm('Do you to install nodejs?')) {
                $this->install();
            }
        }
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    private function check($file, $message): bool
    {
        return $this->task($message, function () use ($file) {
            if (file_exists($file)) {
                return true;
            }
            return false;
        });
    }

    private function install(): void
    {
        $this->task('Installing Nodejs ', function (): void {
            $cmd = 'apt-get install nodejs -y -qqq';
            $response = exec($cmd);
        });
    }
}
