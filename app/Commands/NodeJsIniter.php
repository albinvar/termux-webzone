<?php

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
     *
     * @return void
     */
    public function handle()
    {
        echo exec("clear");
        $this->logo();

        $this->nodeJs = "/data/data/com.termux/files/usr/bin/node";
        $this->npm = "/data/data/com.termux/files/usr/bin/npm";
        $this->checkInstallation();
    }

    public function logo()
    {
        $figlet = new Figlet();
        $this->comment($figlet->setFont(config('logo.font'))->render(config('logo.name')));
    }

    public function checkInstallation()
    {
        $nodejs = $this->check($this->nodeJs, "Checking Nodejs ");
        $npm = $this->check($this->npm, "Checking Npm ");

        if (!$nodejs && !$npm) {
            if ($this->confirm('Do you to install nodejs?')) {
                $this->install();
            }
        }
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

    private function install()
    {
        $this->task("Installing Nodejs ", function () {
            $cmd = "apt-get install nodejs -y -qqq";
            $response = exec($cmd);
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
