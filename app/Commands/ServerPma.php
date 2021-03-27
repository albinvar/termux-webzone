<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ServerPma extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'server:pma
							{--n}
							{--port=8999}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Start PhpMyAdmin locally';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->checkInstallations();
    }
    
    public function checkInstallations()
    {
    	echo shell_exec("clear");
	    $this->info("");
    	$this->logo();
	    $this->info("\n");
    	$port = $this->option('port');
	    $dir = config('pma.PMA_DIR');
    	$cmd = "php -S 127.0.0.1:{$port} -t {$dir}/pma";
		$this->comment("Starting phpmyadmin web interface at : http://127.0.0.1:{$port}");
		$this->info("");
		$this->launch();
	    shell_exec($cmd);
    }
    
    public function logo()
	{
		 $figlet = new \Laminas\Text\Figlet\Figlet();
		 echo $figlet->setFont(config('logo.font'))->render(config('logo.name'));
	}
    
    private function launch()
    {
    	if(!$this->option('n'))
	    {
    	return shell_exec("xdg-open http://127.0.0.1:{$this->option('port')}");
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
