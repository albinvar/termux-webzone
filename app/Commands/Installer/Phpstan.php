<?php

declare(strict_types=1);

namespace App\Commands\Installer;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Helpers\Webzone;
use Storage;

class Phpstan extends Command
{
    protected static $phpstan;
    
    protected static $dir;
    
    protected static $disk;
    
    protected static $cliName;
    
    protected static $packageName;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'installer:phpstan
							{--uninstall}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Install phpstan ';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
    	//set values for properties.
    	static::setConfigs();
    
        $this->webzone = new Webzone();
    
	    // change methods based on action.
        if ($this->option('uninstall')) {
            $this->uninstall();
        } else {
            $this->install();
        }
    }
    
    public static function setConfigs()
    {
    	static::$phpstan = config('phpstan-installer.FULL_PATH');
	    static::$dir = config('phpstan-installer.PATH', '/data/data/com.termux/files/home/.composer/vendor/bin');
		static::$cliName = config('phpstan-installer.NAME', 'laravel');
		static::$packageName = config('phpstan-installer.PACKAGE_NAME');
		static::createDiskInstance();
    }
    
    public static function createDiskInstance()
    {
    	static::$disk = Storage::build([
		    'driver' => 'local',
		    'root' => static::$dir,
		]);
		
    }

    public function checkInstallation(): bool
    {
        return static::$disk->has(static::$cliName);
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    private function uninstall()
    {
        if (! $this->checkInstallation()) {
            $this->error(static::$cliName . ' isn\'t installed yet.');
            return 1;
        }

        if (! $this->confirm('Do you want to uninstall '. static::$cliName .'?')) {
            return 0;
        }   
        
        $this->webzone->clear();
        
        $this->webzone->logo('Laravel Installer', 'comment');
        
        
        $this->newline();
        $this->comment('Unnstalling ' . static::$cliName . '...');
        
        $cmd = exec('composer global remove ' . static::$packageName);
        
        $this->newline();
        $this->comment('Uninstalled successfully..');
        $this->newline();
    }

    private function install()
    {
        if ($this->checkInstallation()) {
            $this->error(static::$cliName . ' is already installed. Use "'. static::$cliName .' --help" to fix directory codes.');
            return false;
        }
        
        $this->webzone->clear();
        
        $this->webzone->logo('Laravel Installer', 'comment');
        
        $this->newline();
        $this->comment('Installing ' . static::$cliName .'...');
        $this->newline();
        
        $cmd = exec('composer global require ' . static::$packageName);
        
        $this->newline();
        $this->comment("Installed successfully. Launch it using \"laravel --help\" command.");
        $this->newline();
        
        $this->initComposerGlobal();
    }

    private function initComposerGlobal(): void
    {
        $this->task('Initialize Command ', function (): void {
            $this->callSilently('composer:global', ['-s' => true]);
        });
    }
}
