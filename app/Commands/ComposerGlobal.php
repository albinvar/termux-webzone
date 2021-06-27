<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Laminas\Text\Figlet\Figlet;
use LaravelZero\Framework\Commands\Command;

class ComposerGlobal extends Command
{
    protected $composer;

    protected $bashrc;

    protected $string;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'composer:global
							{--s|--silent}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Init composer globally';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->callSilently('settings:init');
        $this->composer = config('pma.composer');
        $this->bashrc = config('pma.bashrc');
        $this->checkInstallation();
    }

    private function checkInstallation()
    {
        if (!$this->option('silent')) {
            $this->logo();
        }
        $this->info("\n");
        $is_installed = $this->task("Check whether composer is installed ", function () {
            if (file_exists($this->composer)) {
                return true;
            } else {
                return false;
            }
        });

        $this->checkIfInitialized();
    }

    public function logo()
    {
        $figlet = new Figlet();
        echo $figlet->setFont(config('logo.font'))->render(config('logo.name'));
    }

    private function checkIfInitialized()
    {
        $file = file_get_contents($this->bashrc);
        $this->setString();
        if (strpos(file_get_contents($this->bashrc), $this->string) !== false) {
            $this->comment("\nComposer has already been initiated globally");
        } else {
            $is_initiated = $this->task("configuring composer globally ", function () {
                if ($this->rewrite()) {
                    return true;
                } else {
                    return false;
                }
            });

            if ($is_initiated) {
                $this->info("\nComposer initialised successfully..\n");
                $this->comment("You need to restart your termux session to apply changes..");
            }
        }
    }

    public function setString()
    {
        $this->string = "\n" . config('path');
    }

    private function rewrite()
    {
        $action = file_put_contents($this->bashrc, $this->string, FILE_APPEND | LOCK_EX);
        if ($action) {
            return true;
        } else {
            return false;
        }
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
