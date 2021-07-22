<?php

declare(strict_types=1);

namespace App\Helpers\Jewel;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class Jewel
{
	
	public $path;
	
	public $disk;
	
    public function __construct()
    {
		  
    }
    
    public function createDirectory($path)
    {
    	$this->path = $path.'/.webzone';
		if(!File::isDirectory($this->path)){
			try {
		        File::makeDirectory($this->path, 0777, true, true);
			} catch(\Exception $e) {
				return false;
			}
	    }
		
		return true;
    }
}
