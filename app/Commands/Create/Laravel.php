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
    	$this->dir = $this->getData()['project_dir'];
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
			
		} elseif(!empty($this->dir) && is_dir($this->dir)) {
			
			$this->path = $this->dir;
			
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
    
    public function getData()
    {
        $json_object = file_get_contents(config('settings.PATH').'/settings.json');
        $data = json_decode($json_object, true);
        return $data;
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
