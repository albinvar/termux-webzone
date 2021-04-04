<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Sapper extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'create:sapper
							{--path=/data/data/com.termux/files/home}
							{--name=example}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'create a sapper project';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$this->path = $this->option('path');
	    $this->name = $this->option('name');
        $this->call('nodejs:init');
        echo exec('clear');
        $this->logo();
        $this->newLine();
        $this->install();
        $this->create();
    }
    
    
    public function logo()
	{
		 $figlet = new \Laminas\Text\Figlet\Figlet();
		 echo $figlet->setFont(config('logo.font'))->render("Sapper");
	}
	
	public function install()
	{
		$code = exec('npm install -g degit');
	}
	
	public function create()
	{
		exec('cd '. $this->path .' && npx degit "sveltejs/sapper-template#rollup" '. $this->name .' && cd '. $this->name .' && npm install && npm run dev');
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
