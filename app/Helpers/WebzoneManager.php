<?php

namespace App\Helpers;

class WebzoneManager
{
    public function __construct()
    {
        $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();
    }
    
    
    public function getPort()
    {
    	return config('manager.PORT', 9876);
    }
    
    public function getPath($value=null)
    {
	    	return ($value === 'full') ? config('manager.FULL-PATH', 'manager') : config('manager.PATH', 'manager');
    }
    
    public function getLink()
    {
    	return config('manager.DOWNLOAD_LINK');
    }
    
    public function startServer()
    {
    	return exec("cd {$this->getPath('full')} && xdg-open http://127.0.0.1:{$this->getPort()}/ && php -S 127.0.0.1:{$this->getPort()}");
    }
}
