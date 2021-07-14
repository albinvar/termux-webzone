<?php

namespace App\Helpers;

use App\Helpers\Webzone;
use Illuminate\Support\Facades\Http;

class PhpMyAdmin extends Webzone
{
	public $apiUrl = 'https://www.phpmyadmin.net/home_page/version.json';
	
	public function __construct()
	{
		parent::__construct();
		
	}
	
	public function latestRelease()
	{
		try {
			return Http::acceptJson()->get($this->apiUrl)->json();
		} catch (\Exception $e) {
			return false;
		}
	}
}