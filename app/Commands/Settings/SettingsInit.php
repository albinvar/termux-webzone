<?php

declare(strict_types=1);

namespace App\Commands\Settings;

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
        $this->settings = config('settings.PATH');
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('force')) {
            if ($this->confirm('Do you want to reset your webzone settings?')) {
                $this->create();
            }
        } else {
            $this->checkIfSettingsExist();
        }
    }

    public function create(): void
    {
        $this->task('Creating Required Folders ', function () {
            if ($this->createDirectory()) {
                return true;
            }
            return false;

        
        });

        $this->task('Creating JSON file ', function () {
            if ($this->createSettingsJson()) {
                return true;
            }
            return false;

        
        });
    }

    public function checkIfSettingsExist()
    {
        if (file_exists($this->settings . '/settings.json')) {
            if ($this->validateJson()) {
                $this->createSettingsJson();
            }
            $this->info('Initialized settings');
            return true;
        }
        $this->create();

    
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    private function createDirectory()
    {
        if (! is_dir($this->settings)) {
            mkdir($this->settings);
            return true;
        }
        if (is_dir($this->settings)) {
            return true;
        }

    
    }

    private function createSettingsJson()
    {
        if (! file_exists($this->settings . '/settings.json')) {
            touch($this->settings . '/settings.json');
        }

        $array = config('settings.ARRAY');
        $json_object = json_encode($array);
        $success = file_put_contents($this->settings . '/settings.json', $json_object);
        if ($success === false) {
            return false;
        }
        return true;

    
    }

    private function validateJson()
    {
        $json_object = file_get_contents($this->settings . '/settings.json');
        $data = json_decode($json_object);
        if ($data === null) {
            return true;
        }
        return false;

    
    }
}
