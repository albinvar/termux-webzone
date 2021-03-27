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
    
    public function __construct()
    {
    	parent::__construct();
    	$this->dir = config('pma.PMA_DIR');
    }
    
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->checkInstallation();
    }
    
    public function checkInstallation()
    {
    	if(is_dir($this->dir.'/pma') && file_exists($this->dir.'/pma/config.inc.php')){
	    	if ($this->confirm('Do you want to reinstall PMA?')) {
	        $this->createDirectory();
		    }
    	} else {
    	$this->createDirectory();
	    }
    }
    
    private function createDirectory()
    {
		//$dir = "/data/data/com.termux/files/usr/var/www";
		
		if(!is_dir($this->dir)){
			mkdir($this->dir);
			$this->info('Directory created successfully..');
		}
			return $this->getUrl();
    }
    
    private function downloadPMA()
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
    
    protected function getUrl()
    {
    	$status = $this->isSiteAvailable(config('pma.PMA_URL'));
	    if($status) {
	    	$json = file_get_contents(config('pma.PMA_URL'));
			$data = json_decode($json, TRUE);
			$this->downloadPMACurl($data);
		} else {
			$data = ['PMA_DOWNLOAD_LINK' => config('pma.PMA_DEFAULT_DOWNLOAD_LINK'),];
			$this->downloadPMACurl($data);
			}
    }
    
    private function downloadPMACurl($data)
    {
    	$lines = shell_exec("curl -w '\n%{http_code}\n' {$data['PMA_DOWNLOAD_LINK']} -o {$this->dir}/file.zip");
	    $lines = explode("\n", trim($lines));
		$status = $lines[count($lines)-1];
		$this->checkDownloadStatus($status, $this->dir);
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
    
	public function isSiteAvailable($url){
    // Check, if a valid url is provided
    if(!filter_var($url, FILTER_VALIDATE_URL)){
        return false;
    }

    // Initialize cURL
    $curlInit = curl_init($url);
    
    // Set options
    curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
    curl_setopt($curlInit,CURLOPT_HEADER,true);
    curl_setopt($curlInit,CURLOPT_NOBODY,true);
    curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

    // Get response
    $response = curl_exec($curlInit);
    
    // Close a cURL session
    curl_close($curlInit);

    return $response?true:false;
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
