<?php

namespace App\Helpers;


use App\Helpers\Webzone;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;

class Downloader extends Webzone
{
	
	protected $client;
	
	protected $url;
	
	protected $dir;
	
	public function __construct(String $url, String $dir)
	{
		parent::__construct();
		
		$this->client = new Client(['http_error' => false]);
		
		$this->url = $url;
		$this->dir = $dir;
	}
	
	public function download()
    {
	    $resource = Utils::tryFopen($this->dir, 'w');
		$stream = Utils::streamFor($resource);
		
		try {
			$res = $this->client->request('GET', $this->url, ['save_to' => $stream]);
			return ['ok' => true, 'status_code' => $res->getStatusCode(), 'error' => null];
		} catch (RequestException $e) {
			dd($e);
			return ['ok' => false, 'error' => $e];
		}
    }
}