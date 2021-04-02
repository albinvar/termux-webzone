<?php

namespace App\Commands;

use App\ConfigIniter;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Illuminate\Support\Facades\Artisan;

class Mysql extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'server:mysql
							{--s|--stop}
							{--port=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Start MySql Services';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$this->setPort();
    	$this->mysql = config('pma.MYSQL_PATH');
    	pcntl_async_signals(true);

    // Catch the cancellation of the action
    pcntl_signal(SIGINT, function () {
        $this->comment("\nShutting down...\n");

        $this->stop();
    });   

        $this->checkInstallation();
    }
    
    public function checkInstallation()
    {
    	if(!file_exists($this->mysql)){
	    	$source = $this->choice(
        "mysql doesn't seem to be installed, do you want to install it now ?",
        [1 => 'install now', 0 => 'cancel']
		    );
		
		if($source == 'install now' || $source == 1) {
			$this->call('install:mysql');
			}
		if($source == 'cancel' || $source == 0) {
			$this->info("Good bye");
			}
		} elseif($this->option('stop')) {
			$this->stop();
    	} else {
	    	$this->start();
	    }
    }
    
    private function setPort()
    {
    	if(!empty($this->option('port'))){
		    $this->port = $this->option('port');
    	} elseif(!empty($this->getPort())){
	    	$this->port = $this->getPort();
		} else {
			$this->port = config('pma.MYSQL_PORT');
		}
    }
    
    
    private function start()
    {
    	$this->logo();
	    $this->comment("mysql Services Started....");
	    $this->line("\n");
    	$cmd = exec("mysqld --port={$this->port} --gdb");
    }
    
    private function stop()
    {
    	$this->task("Kill Mysql processes ", function () {
	        $cmd = "killall -9 mysqld 2> /dev/null";
		    $response = exec($cmd);
        });
    }
    
    public function logo()
	{
		 $figlet = new \Laminas\Text\Figlet\Figlet();
		echo $figlet->setFont(config('logo.font'))->render(config('logo.name'));
	}
	
	public function getPort()
    {
    	$json_object = file_get_contents(config('settings.PATH').'/settings.json');
		$data = json_decode($json_object, true);
    	return $data['mysql_port'];
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
