<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use LaravelZero\Framework\Commands\Command;

class Downloader
{
	
	protected static $client;
	
	public function __construct()
	{
		
	}
	
	public static function download($link=null, $dir=null)
    {
	    self::$client = new Client(['http_errors' => false]);
	
	    $resource = Utils::tryFopen('test/pma.zip', 'w');
		$stream = Utils::streamFor($resource);
		
		try {
			$res = self::$client->request('GET', 'https://files.phpmyadmin.net/phpMyAdmin/5.1.0/phpMyAdmin-5.1.0-all-languages.zip', ['save_to' => $stream]);
		} catch (RequestException $e) {
		    $response = $e->getMessage();
			return false;
		}

		
        switch ($res->getStatusCode()) {
            case 000:
                $this-error('Cannot connect to Server');
                break;
            case 200:
                $this->comment("\nDownloaded Successfully...!!!");
                $this->runTasks();
                break;
            case 404:
                $this->error('File not found on server..');
                break;
            default:
                $this->error('An Unknown Error occurred...');
        }
    }
}