<?php

declare(strict_types=1);

namespace App\Commands\Installer;

use Illuminate\Console\Scheduling\Schedule;
use Laminas\Text\Figlet\Figlet;
use LaravelZero\Framework\Commands\Command;
use App\Helpers\Webzone;
use App\Helpers\Downloader;
use Illuminate\Support\Facades\Storage;

class Symfony extends Command
{
    protected static $symfony;

    protected static $dir;
    
    protected static $cliName = 'symfony';
    
    protected static $disk;
    
    protected static $link;
    
    protected static $needle = 'Symfony CLI version';

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'installer:symfony';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Install Symfony CLI ';
    
    public function __construct()
    {
        parent::__construct();
        
        $this->webzone = new Webzone();
    }
    
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->callSilently('settings:init');
        
        self::setConfigs();
        
        $this->install();
    }
    
    public static function setConfigs()
    {
    	static::$symfony = config('symfony.CLI_PATH');
	    static::$dir = config('symfony.PATH', '/data/data/com.termux/files/usr/bin');
		static::$link = config('symfony.CLI_DOWNLOAD_LINK');
    }

    public function install()
    {
        if ($this->checkInstallation()) {
            $this->error('Symfony CLI is already installed. Use "symfony help" to show all commands.');
            return 1;
        }

        $this->webzone->clear();
        
        $this->webzone->logo('Symfony', 'comment');
        
        $this->newline();
        $this->comment("Installing Symfony CLI...");
        $this->newline();
        
        $this->runTasks();
        
    }

    public function checkInstallation(): bool
    {
        if (file_exists(static::$symfony)) {
            return true;
        }
        return false;
    }


    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    private function checkDownloadStatus($status, $dir): void
    {
        switch ($status) {
            case 000:
                $this->error('Cannot connect to Server');
                break;
            case 200:
                $this->comment("\nDownloaded Successfully...!!!");
                $this->runTasks();
                break;
            case 404:
                $this->error('File not found on server..');
                break;
            default:
                $this->error('An Unknown Error occurred...');
        }
    }

    private function runTasks(): void
    {
	    $this->download();
	
		$this->task('setting permissions', function() {
			return chmod(static::$symfony, 0775);
		});
	
        $this->task('verifying command ', function () {
            if (str_contains(shell_exec('symfony version'), static::$needle) && file_exists(static::$symfony)) {
                return true;
            }
            return false;
        });
    }
    
    private function download(): void
    {
    	static::$disk = Storage::build([
		    'driver' => 'local',
		    'root' => static::$dir,
		]);
		
        $downloadTask = $this->task('Downloading resources ', function () {
            $this->downloader = new Downloader(static::$link, static::$cliName, static::$disk);
            $response = $this->downloader->download();

            if ($response['ok']) {
                return true;
            }
            $this->error($response['error']->getMessage());
            return false;
        });
    }
}
