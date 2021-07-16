<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Helpers\Webzone;

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
    
    public function __construct()
    {
        parent::__construct();
        
        $this->webzone = new Webzone();
    }
    
    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->callSilently('settings:init');
        $this->composer = config('pma.composer');
        $this->bashrc = config('pma.bashrc');
        $this->checkInstallation();
    }

    public function setString(): void
    {
        $this->string = "\n" . config('path');
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    private function checkInstallation(): void
    {
        $this->newline();
        $is_installed = $this->task('Check whether composer is installed ', function () {
            if (file_exists($this->composer)) {
                return true;
            }
            return false;
        });

        $this->checkIfInitialized();
    }

    private function checkIfInitialized(): void
    {
        $file = file_get_contents($this->bashrc);
        $this->setString();
        if (strpos(file_get_contents($this->bashrc), $this->string) !== false) {
            $this->comment("\nComposer has already been initiated globally");
        } else {
            $is_initiated = $this->task('configuring composer globally ', function () {
                if ($this->rewrite()) {
                    return true;
                }
                return false;
            });

            if ($is_initiated) {
                $this->info("\nComposer initialised successfully..\n");
                $this->comment('You need to restart your termux session to apply changes..');
            }
        }
    }

    private function rewrite()
    {
        $action = file_put_contents($this->bashrc, $this->string, FILE_APPEND | LOCK_EX);
        if ($action) {
            return true;
        }
        return false;
    }
}
