<?php

declare(strict_types=1);

namespace App\Helpers\Jewel;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class Jewel
{
	
	public $path;
	
	public $disk;
	
	public $mainPath;
	
    public function __construct()
    {
		  
    }
    
    public function createDirectory($path)
    {
    	$this->mainPath = $path;
    	$this->path = $path.'/.webzone';
		if(!File::isDirectory($this->path)){
			try {
		        File::makeDirectory($this->path, 0777, true, true);
				$this->updateGitIgnore();
				$this->createConfigFile();
			} catch(\Exception $e) {
				return false;
			}
	    } else {
			$this->updateGitIgnore();
			return true;
		}
    }
    
    
    private function updateGitIgnore()
    {
    	try {
	    	File::append($this->mainPath.'/.gitignore', '.webzone/', null);
		} catch(\Exception $e) {
			
		}
    }
    
    private function createConfigFile()
    {
    	File::put($this->path.'/webzone.json', $this->generateJson());
    }
    
    private function generateJson()
    {
    	$array = ['created_at' => time()];
	    return json_encode($array);
    }
}
