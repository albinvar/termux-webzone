<?php

declare(strict_types=1);

namespace App\Commands\Settings;

use App\Helpers\Settings\Handler;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class SettingsInit extends Command
{
    public $settings;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'settings:init
							{--f|--force}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Init Settings Json file';

    public function __construct()
    {
        parent::__construct();
        $this->settings = new Handler();
        $this->path = config('settings.PATH');
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if ($this->option('force')) {
            if ($this->confirm('Do you want to reset your webzone settings?')) {
                $this->settings->setStrictMode(true);
            }
        }

        $this->init();
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    private function init()
    {
        if ($this->settings->isSettled() && ! $this->settings->strictMode) {
            $this->error('Already initialized. Please use -f|--force option reinitialise settings.');
            return true;
        }

        $this->task('Creating Required Folders ', function (): void {
            $this->settings->createDir();
        });

        $this->task('Flashing default settings ', function (): void {
            $this->settings->flash();
        });

        $this->task('Validating settings ', function (): void {
            $this->settings->validate();
        });
    }
}
