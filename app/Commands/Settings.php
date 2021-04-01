<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Illuminate\Support\Facades\File;

class Settings extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'settings';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Settings for webzone';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$this->callSilently('settings:init');
        $this->showSettings();
    }
    
    public function logo()
	{
		 $figlet = new \Laminas\Text\Figlet\Figlet();
		echo $figlet->setFont(config('logo.font'))->render(config('logo.name'));
	}
    
    public function showSettings()
    {
    	echo exec('clear');
    	$this->logo();
	    $this->newLine();
    	$this->showList();
    }
    
    public function getOptions()
    {
    	$json_object = file_get_contents(config('settings.PATH').'/settings.json');
		$data = json_decode($json_object, false);
    	return $data;
    }
    
    public function options()
    {
    	$data = $this->getOptions();
	    
    }
    
    private function showList()
    {
    	$source = $this->choice(
        'What would you like to modify',
        [1 => 'Project Root', 'Localhost Port', 'MySql Port', 'Ngrok port', 'PhpMyAdmin Port', 9 => 'Exit']
    );

    $this->info("Source chosen is $source");
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
