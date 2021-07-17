<?php

declare(strict_types=1);

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Utils;
use Storage;

class Downloader extends Webzone
{
    protected $client;

    protected $url;

    protected $file;

    protected $downloadedFile;

    protected $saveTo;

    public function __construct(string $url, string $file, string $disk = 'tmp')
    {
        parent::__construct();

        $this->client = new Client(['http_error' => false]);

        $this->url = $url;
        $this->file = $file;

        $disk !== 'tmp' ? $this->disk = $disk : $this->disk = 'tmp';

        $this->downloadFile = Storage::disk($this->disk)->getAdapter()->getPathPrefix().'/'.$this->file;
    }

    public function download()
    {
        if (! $this->createDirIfNotExists()) {
            return ['ok' => false, 'error' => 'Cannot create directory'];
        }

        $resource = Utils::tryFopen($this->downloadFile, 'w');
        $stream = Utils::streamFor($resource);

        try {
            $res = $this->client->request('GET', $this->url, ['save_to' => $stream]);
            return ['ok' => true, 'status_code' => $res->getStatusCode(), 'error' => null, 'path' => $this->downloadFile];
        } catch (RequestException $e) {
            return ['ok' => false, 'error' => $e];
        }
    }

    public function clean()
    {
        try {
            Storage::disk($this->disk)->delete($this->file);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function createDirIfNotExists()
    {
        try {
            Storage::makeDirectory($this->saveTo);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
