<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Laminas\Text\Figlet\Figlet;
use LaravelZero\Framework\Commands\Command;

class NodeJsIniter extends Command
{
    protected $nodeJs;

    protected $npm;

    protected $nodeStatus;

    protected $npmStatus;

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
     * @return mixed
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
        $a = $this->checkNodeJs();
        $b = $this->checkNpm();

        if ($this->nodeStatus && $this->npmStatus) {
        } else {
            if ($this->confirm('Do you to install nodejs?')) {
                $this->install();
            }
        }
    }


    private function checkNodeJs()
    {
        $this->task("Checking Nodejs ", function () {
            if (file_exists($this->nodeJs)) {
                $this->nodeStatus = true;
                return true;
            } else {
                $this->nodeStatus = false;
                return false;
            }
        });
    }

    private function checkNpm()
    {
        $this->task("Checking Npm ", function () {
            if (file_exists($this->npm)) {
                $this->npmStatus = true;
                return true;
            } else {
                $this->npmStatus = false;
                return false;
            }
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
