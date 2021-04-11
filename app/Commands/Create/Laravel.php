<?php

namespace App\Commands\Create;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Laravel extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'create:laravel
							{name?}
							{--path=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create laravel projects';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$this->init();
        $this->create();
    }
    
    private function init()
    {
    	//name of project
		if(!empty($this->argument('name')))
		{
			$this->name = $this->argument('name');
		} else {
			$this->name = 'something';
		}
    
    
    	//set path
		if(!empty($this->option('path')))
		{
			$this->path = $this->option('path');
			
		} elseif(!empty(config('pma.PROJECT_BASE_PATH')) && is_dir(config('pma.PROJECT_BASE_PATH'))) {
			
			$this->path = config('pma.PROJECT_BASE_PATH');
			
		} else {
			$this->path = '/sdcard';
		}
		
		
		
    }
    
    private function create()
    {
    	$cmd = "cd {$this->path} && composer create-project laravel/laravel \"{$this->name}\"";
	    $this->exec($cmd);
    }
    
    private function exec($command)
    {
    	$this->line(exec($command));
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
