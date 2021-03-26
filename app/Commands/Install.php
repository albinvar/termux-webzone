<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Install extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Install PMA';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->createDirectory();
    }
    
    private function createDirectory()
    {
	    //$lines = shell_exec("");
		//$this->info($lines);
		
		//$dir = "/data/data/com.termux/files/usr/var/www";
		$dir = "/storage/emulated/0/laravel-zero/termux-pma-installer/test/";
		
		if(!is_dir($dir)){
			mkdir($dir);
			$this->info('Directory created successfully..');
		}
			return $this->downloadPMACurl($dir);
    }
    
    private function downloadPMA($dir)
    {
    	$url = "https://mattstauffer.com/assets/images/logo.svg";
		$fp = fopen($dir . 'name.zip', "w+");

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_exec($ch);
		$st_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		fclose($fp);

		if($st_code == 200)
			 $this->info('File downloaded successfully!');
		else {
			 $this->error('Error downloading file!');
		}
	
    }
    
    private function downloadPMACurl($dir)
    {
    	$lines = shell_exec("curl -w '%{http_code}\n' 'https://mattstauffer.com/assets/images/logo.svg -o test.svg'");
	    $lines = explode("\n", trim($lines));
		$this->error($lines[count($lines)-1]);
	
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
