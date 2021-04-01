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
    	$this->showList();
    }
    
    public function getOptions()
    {
    	$json_object = file_get_contents(config('settings.PATH').'/settings.json');
		$data = json_decode($json_object, true);
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
	    switch($source)
		{
			case 'Project Root':
				$body = "Project Root";
				$key = 'project_dir';
				$this->showDefault();
				$path = $this->dirUpdater();
				if(!$this->checkDir($path)){
					$this->error('The path you have provided seems to be invalid. Try again..');
					sleep(4);
					exec('clear');
					return $this->call('settings');
				}
				$type = "normal";
				$this->edit($body, $key, $path, $type);
				break;
				
			case 'Localhost Port':
				$body = "Localhost Port";
				$key = 'php_port';
				$this->showDefault();
				$port = $this->portUpdater();
				
				if(!is_numeric($port) && strlen($port) > 5){
					$this->error('The port you have provided seems to be invalid. Try again..');
					sleep(3);
					exec('clear');
					return $this->call('settings');
				}
				$type = "normal";
				$this->edit($body, $key, $port, $type);
				break;

			case 'Exit':
				$this->info('Good Bye!');
				break;
		}
    }
    
    private function edit($description, $key, $default, $type)
    {
    	$data = $this->getOptions();
	    $data[$key] = $default;
		$this->update($data);
    
    }
    
    private function update($data)
    {
    	$json_object = json_encode($data);
		file_put_contents(config('settings.PATH').'/settings.json', $json_object);
		$this->newLine();
    	$this->comment('Updated successfully...');
	    sleep(3);
	    return $this->call('settings');
    }
    
    private function checkDir($path)
    {
    	if(is_dir($path))
	    {
			return true;
		} else {
			return false;
		}
    }
    
    private function showDefault($q="Do you want to change this setting?")
    {
    	echo exec('clear');
	    $this->logo();
		$this->newLine();
    	if($this->confirm($q))
	    {
		return true;
		} else {
			return $this->call('settings');
		}
			
    }
    
    private function dirUpdater($q="Enter folder path")
    {
    	echo exec('clear');
	    $this->logo();
		$this->newLine();
    	$path = $this->ask($q);
		return $path;
			
    }
    
    private function portUpdater($q="Enter a specific port")
    {
    	echo exec('clear');
	    $this->logo();
		$this->newLine();
    	$port = $this->ask($q);
		return $port;
			
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
