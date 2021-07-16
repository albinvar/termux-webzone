<?php

namespace App\Helpers;

use App\Helpers\Webzone;
use Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class PhpMyAdmin extends Webzone
{
    public $endpoint = 'https://www.phpmyadmin.net/home_page/version.json';

    public function __construct()
    {
        parent::__construct();
    }

    public function latestRelease()
    {
        try {
            return Http::acceptJson()->get($this->endpoint)->json();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function configurator(String $path, String $filename)
    {
        $blowfish = hash('tiger128,4', date("hisa"));

        $oldLines = [
            '$cfg[\'Servers\'][$i][\'AllowNoPassword\'] = false;',
            '$cfg[\'blowfish_secret\'] = \'\'; /* YOU MUST FILL IN THIS FOR COOKIE AUTH! */',
        ];

        $newLines = [
            '$cfg[\'Servers\'][$i][\'AllowNoPassword\'] = true;',
            '$cfg[\'blowfish_secret\'] = \''.$blowfish.'\';',
        ];

        try {
            $contents = Storage::get($path.'/'.$filename);
            $str = str_replace($oldLines, $newLines, $contents);
            Storage::disk('local')->put($path.'/config.sample.inc.php', $str);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    public function removeOld()
    {
    	$existingDirectories = Storage::disk('local')->directories('www');
    
		$results = [];
		
		foreach($existingDirectories as $key => $dir) {
	        if (strpos($dir, 'phpMyAdmin') !== FALSE)
			{
	           $results[] = Storage::deleteDirectory($dir);
			}
		}
		
		return !in_array(false, $results, true);
    }
    
    public function updateRoot($path)
    {
    	$json_object = file_get_contents(config('settings.PATH') . '/settings.json');
        $data = json_decode($json_object, true);
        $data['pma_root'] = $path;
        
    	$json_object = json_encode($data);
        if(file_put_contents(config('settings.PATH') . '/settings.json', $json_object))
        {
        	return true;
        }
	    return false;
    }
}
