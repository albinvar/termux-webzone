<?php

namespace App\Helpers;


use App\Helpers\Webzone;
use GuzzleHttp\Client;
use Storage;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Filesystem\Filesystem;

class Downloader extends Webzone
{
	
	protected $client;
	
	protected $url;
	
	protected $file;
	
	protected $downloadedFile;
	
	protected $saveTo;
	
	public function __construct(String $url, String $file, String $saveTo='tmp')
	{
		parent::__construct();
		
		$this->client = new Client(['http_error' => false]);
		
		$this->url = $url;
		$this->file = $file;
		
		$this->downloadFile = Storage::disk('local')->getAdapter()->getPathPrefix().'/'.$this->file;
		
		$this->saveTo = $saveTo;
	}
	
	public function download()
    {
    	if(!$this->createDirIfNotExists()) { return ['ok' => false, 'error' => 'Cannot create directory']; }
	    
	    $resource = Utils::tryFopen($this->downloadFile, 'w');
		$stream = Utils::streamFor($resource);
		
		try {
			$res = $this->client->request('GET', $this->url, ['save_to' => $stream]);
			return ['ok' => true, 'status_code' => $res->getStatusCode(), 'error' => null, 'path' => $this->downloadFile];
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
    
    public function clean()
    {
    	try {
	    	Storage::disk('local')->delete($this->file);
			return true;
		} catch(\Exception $e) {
			return false;
		}
    }
}