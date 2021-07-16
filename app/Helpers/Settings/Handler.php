<?php

namespace App\Helpers\Settings;

use LaravelZero\Framework\Commands\Command;
use Storage;

class Handler extends Command
{
    public function __construct()
    {
        $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();
    }
    
    public function setStrictMode($mode=false)
    {
    	$this->strictMode = $mode;
    }
    
    public function getArray()
    {
    	return config('settings.ARRAY');
    }
    
    public function getSettings()
    {
    	//
    }
    
    public function init()
    {
    	$this->task('Creating Webzone Folders ', function () {
	    	$this->createDir();
		});
    }
    
    public function createDir()
    {
    	try {
            Storage::disk('local')->makeDirectory('/');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    
    
}