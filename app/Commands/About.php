<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class About extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'about';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->about();
    }
    
    public function about()
    {
    	$this->logo();
	    $this->comment("  ".app('git.version'));
		$this->newLine();
		$this->info('Termux Webzone is a CLI application which provides a ton of features for web developers to build, run and test their php applications within the limits of android. The application is designed only to work with Termux.');
		$this->newLine();
		$this->comment('  github: https://github.com/albinvar/termux-webzone');
		$this->newLine();
		$this->credits();
    }
    
    public function logo()
	{
		 $figlet = new \Laminas\Text\Figlet\Figlet();
		echo $figlet->setFont(config('logo.font'))->render(config('logo.name'));
	}
	
	public function credits()
	{
		$headers = ['Developers', 'role'];

	    $data = [
	        ['Albin', 'Developer']
	    ];
    
	    $this->table($headers, $data);
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
