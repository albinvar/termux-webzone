<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class DevelopmentServer extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'server:dev
							{--port=8088}
							{--n}';
    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Start localhost for development';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$this->path = config('pma.PROJECT_BASE_PATH');
        $this->checkInstallation();
    }
    
    public function checkInstallation()
    {
    	if(!is_dir($this->path)){
	    	mkdir($this->path);
			$this->comment('Directory created..');
			$this->createIndex($this->path);
	    }
	 $this->start();
    }
    
    private function start()
    {
	    echo exec('clear');
    	$this->logo();
	    $this->comment("Starting Localhost Server....");
	    $this->line("\n");
		$this->launch();
    	$cmd = exec("php -S 127.0.0.1:{$this->option('port')} -t {$this->path}");
    }
    
    private function launch()
    {
    	if(!$this->option('n'))
	    {
    	return shell_exec("xdg-open http://127.0.0.1:{$this->option('port')}");
	    }
    }
    
    private function createIndex()
    {
    	$cmd = exec("touch {$this->path}/index.php && echo -e \"<center><h1>Created Successfully</h1><p>Everything just went fine.....</p><\center>\" >> {$this->path}/index.php");
    }
    
    public function logo()
	{
		 $figlet = new \Laminas\Text\Figlet\Figlet();
		echo $figlet->setFont(config('logo.font'))->render(config('logo.name'));
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
