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
    	$this->dir = "/storage/emulated/0/laravel-zero/termux-pma-installer/test/";
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
    	$lines = shell_exec("curl -w '\n%{http_code}\n' http://localhost:8888/phpMyAdmin.zip -o {$dir}/file.zip");
	    $lines = explode("\n", trim($lines));
		$status = $lines[count($lines)-1];
		$this->checkDownloadStatus($status, $dir);
    }
    
    
    private function checkDownloadStatus(Int $status, $dir)
    {
    	switch ($status) {
  case 000:
    $this->error("Cannot connect to Server");
    break;
  case 200:
    $this->comment("\nDownloaded Successfully...!!!");
    $this->runTasks();
    break;
  case 404:
    $this->error("File not found on server..");
    break;
  default:
    $this->error("An Unknown Error occurred...");
}
    }
    
    private function runTasks()
    {
    	$this->task("Extracting PMA ", function () {
     	
            if($this->unzip($this->dir))
            { return true; }
            else
			{ return false; }
        });
        $this->task("Set Configuration File ", function () {
     	
            if($this->setPmaConfig($this->dir))
            { return true; }
            else
			{ return false; }
        });
    }
    
    
    private function unzip($dir)
    {
    	
	    $zip = new \ZipArchive();
		$file = $dir."/file.zip";
		
    // open archive
    if ($zip->open($file) !== TRUE) {
        return false;
    }
    // extract contents to destination directory
    $zip->extractTo($dir.'/pma');
    // close archive
    $zip->close();
        return true;
    }
    
    private function setPmaConfig()
    {
	    if(file_exists($this->dir.'/pma/config.sample.inc.php'))
		{
			if(@rename($this->dir.'/pma/config.sample.inc.php', $this->dir.'/pma/config.inc.php')===true)
			{
				return true;
			} else {
				return false;
			}
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
