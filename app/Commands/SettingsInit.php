<?php

namespace App\Commands;

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
    protected $signature = 'settings:init';

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
     *
     * @return mixed
     */
    public function handle()
    {
        $this->checkIfSettingsExist();
    }
    
    public function checkIfSettingsExist()
    {
    	if(file_exists($this->settings))
	    {
			return true;
		} else {
			$this->create();
		}
    }
    
    public function create()
    {
    	$this->createDirectory();
    
    	$this->createSettingsJson();
    }
    
    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
