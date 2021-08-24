<?php

namespace App\Helpers;

use Illuminate\Filesystem\FilesystemAdapter;
use Storage;

class ComposerInstallerManager
{
	
	private static $_instance = null;
	
	private static $package;
	
	private static $cliName;
	
	private static $disk;
	
	public static function package(string $packageName, string $cliName=null, $disk=null)
    {
    	self::$package = $packageName;
	    self::$cliName = $cliName;
		self::$disk = ($disk instanceof FilesystemAdapter)
						? $disk
						: null;
						
        self::$_instance = (self::$_instance === null) ? new self : null;

        return self::$_instance;
    }
	
	public function install()
	{
		exec('composer global require ' . static::$package);
		
		if(is_null(self::$disk))
		{
			return true;
		}
		
		return $disk->has(self::$cliName);
	}
	
	public function uninstall()
	{
		exec('composer global remove ' . static::$package);
		
		if(is_null(self::$disk))
		{
			return true;
		}
		
		return ! self::$disk->has(self::$cliName);
	}
}