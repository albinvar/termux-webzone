<?php

namespace App\Helpers;

use ZipArchive;
use App\Helpers\Webzone;

class Zipper extends Webzone
{
	
	protected $client;
	
	protected $url;
	
	protected $dir;
	
	public function __construct(String $dir, String $filename, String $extractFolder)
	{
		parent::__construct();
		
		$this->dir = $dir;
		$this->extractFolder = $extractFolder;
		$this->filename = $filename;
		$this->zip = new ZipArchive();
	}
	
	public function unzip()
    {
        if($this->zip->open($this->filename) !== true){
			 return false;
		}
        
        $this->zip->extractTo($this->extractFolder, $this->filename);
        
        $this->zip->close();
        
        return true;
    }
}