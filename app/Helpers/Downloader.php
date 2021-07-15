<?php

namespace App\Helpers;


use App\Helpers\Webzone;
use GuzzleHttp\Client;
use Storage;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;

class Downloader extends Webzone
{
	
	protected $client;
	
	protected $url;
	
	protected $dir;
	
	protected $saveTo;
	
	public function __construct(String $url, String $dir, String $saveTo='tmp')
	{
		parent::__construct();
		
		$this->client = new Client(['http_error' => false]);
		
		$this->url = $url;
		$this->dir = $dir;
		
		$this->saveTo = $saveTo;
	}
	
	public function download()
    {
    	if(!$this->createDirIfNotExists()) { return ['ok' => false, 'error' => 'Cannot create directory']; }
	    
	    $resource = Utils::tryFopen($this->dir, 'w');
		$stream = Utils::streamFor($resource);
		
		try {
			$res = $this->client->request('GET', $this->url, ['save_to' => $stream]);
			return ['ok' => true, 'status_code' => $res->getStatusCode(), 'error' => null, 'path' => $this->dir];
		} catch (RequestException $e) {
			return ['ok' => false, 'error' => $e];
		}
    }
    
    
    private function createDirIfNotExists()
    {
    	  try {
	        Storage::makeDirectory($this->saveTo);
			return true;
		} catch(\Exception $e) {
			return false;
		}
    }
}