<?php

declare(strict_types=1);

namespace App\Commands\Installer;

use LaravelZero\Framework\Commands\Command;
use App\Helpers\Webzone;
use App\Helpers\ComposerInstallerManager as Manager;
use Storage;

class PhpCsFixer extends Command
{
    protected static $fixer;
    
    protected static $dir;
    
    protected static $disk;
    
    protected static $cliName;
    
    protected static $packageName;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'installer:fixer
							{--uninstall}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Install php-cs-fixer';

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
    	static::$fixer = config('php-cs-fixer.FULL_PATH');
	    static::$dir = config('php-cs-fixer.PATH', '/data/data/com.termux/files/home/.composer/vendor/bin');
		static::$cliName = config('php-cs-fixer.NAME', 'php-cs-fixer');
		static::$packageName = config('php-cs-fixer.PACKAGE_NAME');
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
        
        $this->webzone->logo('php  cs  fixer', 'comment');
        
        $this->newline();
        $this->comment('Unnstalling ' . static::$cliName . '...');
        
        $status = Manager::package(static::$packageName, static::$cliName, static::$disk)->uninstall();
        
        $this->newline();
        ($status) ? $this->comment('Uninstalled successfully..')
				  : $this->error('Uninstall failed..');
        $this->newline();
    }

    private function install()
    {
        if ($this->checkInstallation()) {
            $this->error(static::$cliName . ' is already installed. Use "'. static::$cliName .' fix <folder_name>" to fix directory codes.');
            return false;
        }
        
        $this->webzone->clear();
        
        $this->webzone->logo('php  cs  fixer', 'comment');
        
        $this->newline();
        $this->comment('Installing ' . static::$cliName .'...');
        $this->newline();
        
        $status = Manager::package(static::$packageName, static::$cliName, static::$disk)->install();
        
        $this->newline();
        ($status) ? $this->comment("Installed successfully. Launch it using \"php-cs-fixer --help\" command.")
				  : $this->error('Installation failed..');
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
