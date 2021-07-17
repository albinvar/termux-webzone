<?php

declare(strict_types=1);

namespace App\Helpers;

use ZipArchive;

class Zipper extends Webzone
{
    protected $client;

    protected $url;

    protected $dir;

    public function __construct(string $dir, string $filename, string $extractFolder)
    {
        parent::__construct();

        $this->dir = $dir;
        $this->extractFolder = $extractFolder;
        $this->filename = $filename;
        $this->zip = new ZipArchive();
    }

    public function unzip()
    {
        if ($this->zip->open($this->filename) !== true) {
            return false;
        }

        if (! $this->zip->extractTo($this->extractFolder)) {
            return false;
        }

        $this->zip->close();

        return true;
    }
}
