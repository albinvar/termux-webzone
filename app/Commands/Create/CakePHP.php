<?php

declare(strict_types=1);

namespace App\Commands\Create;

use Illuminate\Console\Scheduling\Schedule;
use Laminas\Text\Figlet\Figlet;
use App\Helpers\ComposerPackageInstaller;
use App\Helpers\Webzone;
use LaravelZero\Framework\Commands\Command;

class CakePHP extends Command
{
    protected $dir;

    protected $path;

    
    protected $signature = 'create:cakephp
							{name=blog}
							{--path=}';
							
    protected $description = 'Create cakephp projects';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->callSilently('settings:init');
        
        $this->installer = new ComposerPackageInstaller("CakePhP", 'cakephp');
        $this->webzone = new Webzone;
        
        $this->webzone->clear();
        $this->webzone->logo('CAKE PHP', 'comment');
        $this->init();
    }
    
    private function init()
    {
    	$path = $this->option('path');
    
	    $this->task('Setting up Installer', function () use($path){
	    	$this->installer->setProperties($this->argument('name'), $path);
		    $this->installer->setComposerPackage("cakephp/cakephp");
		});
		
		$this->task('Checking if project exists', function () {
			if($this->installer->checkIfProjectExists())
			{
				$this->status = false;
				$this->newline(2);
                $this->error('Project with same name already exists');
                $this->newline();
                return false;
			}
			$this->status = true;
			return true;
		});
		
		if($this->status) {
			$this->install();
		}
	  
    }
    
    private function install()
    {
	    $this->newline();
        $this->info('Creating CakePHP Application...');
        $this->newline();
        $this->installer->install();
        $this->newline();
        $this->info(" 🎉 CakePHP app created successfully at {$this->installer->mainPath}/{$this->installer->name}. 🥳");
        $this->newline();
        $this->comment("   🤙 Create Something Awesome 😉");
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
