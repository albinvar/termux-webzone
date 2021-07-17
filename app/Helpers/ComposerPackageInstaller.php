<?php

namespace App\Helpers;

use Storage;
use App\Helpers\Webzone;
use App\Helpers\Settings\Handler;
use LaravelZero\Framework\Commands\Command;

class ComposerPackageInstaller extends Command
{
    public $path;

    public $framework;

    public $name;

    public $frameworkDisk;


    public function __construct($framework, $disk=null)
    {
        $this->framework = $framework;
        $this->frameworkDisk = $disk;
        $this->settings = new Handler();
        $this->projectsPath = $this->settings->getSettingsAsArray()['project_dir'];
        $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();
    }

    public function setProperties($name="blog", $path=null, $type=null)
    {
        $this->name = $name;
        $this->path = (is_null($path)) ? Storage::disk('projects')->makeDirectory($this->frameworkDisk) : $path;
        $this->type = null;
    }

    public function setComposerPackage($package, $attributes=null)
    {
        $this->package = $package;
    }

    public function install()
    {
        if (is_bool($this->path)) {
            try {
                $path = Storage::disk('projects')->getAdapter()->getPathPrefix() . $this->frameworkDisk;
            } catch (\Exception $e) {
                $path = '/sdcard/www';
            }
        } else {
            $path = $this->path;
        }
        $cmd = "cd {$path} && composer create-project --prefer-dist {$this->package} '{$this->name}'";
        exec($cmd);
    }

    public function checkIfProjectExists()
    {
        if (is_bool($this->path)) {
            if (Storage::disk('projects')->exists($this->frameworkDisk.'/'.$this->name)) {
            	
                return true;
            }
            return false;
        } else {
            if (is_dir($this->path .'/'. $this->name)) {
            	
                return true;
            }
            return false;
        }
    }
}
