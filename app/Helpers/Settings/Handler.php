<?php

namespace App\Helpers\Settings;

use LaravelZero\Framework\Commands\Command;
use Storage;

class Handler extends Command
{
	
	public $strictMode;
	
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
    	try {
	    	return Storage::disk('local')->get('settings.json');
		} catch (\Exception $e) {
			return false;
		}
    }
    
    public function getSettingsAsArray()
    {
    	try {
	    	$settings = Storage::disk('local')->get('settings.json');
		} catch (\Exception $e) {
			return false;
		}
		
		$array = json_decode($settings, true);
		
		if (json_last_error() === JSON_ERROR_NONE) {
            return $array;
        }
        return false;
    }
    
    public function init()
    {
    	//
    }
    
    public function isSettled(): Bool
    {
    	return Storage::disk('local')->exists('settings.json');
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
    
    public function flash()
    {
       $settings = json_encode($this->getArray());
        
       try {
            Storage::disk('local')->put('/settings.json', $settings);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    public function validate()
    {
    	try {
	        $jsonObject = Storage::disk('local')->get('settings.json');
		} catch (\Exception $e) {
			return false;
		}
		
		json_decode($jsonObject);
		
        if (json_last_error() === JSON_ERROR_NONE) {
            return true;
        }
        return false;
    }
    
    public function edit()
    {
    	//
    }
    
}