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
    	if(file_exists($this->settings.'/settings.json'))
	    {
			return true;
		} else {
			$this->create();
		}
    }
    
    public function create()
    {
    	$this->task("Creating Required Folders ", function () {
     	
            if($this->createDirectory())
            { return true; }
            else
			{ return false; }
        });
    
    	$this->task("Creating JSON file ", function () {
     	
            if($this->createSettingsJson())
            { return true; }
            else
			{ return false; }
        });
    }
    
    private function createDirectory()
    {
		
		if(!is_dir($this->settings)){
			mkdir($this->settings);
			return true;
		} else {
			return false;
			}
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
