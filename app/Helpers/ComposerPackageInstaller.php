<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Helpers\Jewel\Jewel;
use App\Helpers\Settings\Handler;
use LaravelZero\Framework\Commands\Command;
use Storage;

class ComposerPackageInstaller extends Command
{
    public $path;

    public $framework;

    public $mainPath;

    public $name;

    public $frameworkDisk;

    public function __construct($framework, $disk = null)
    {
        $this->framework = $framework;
        $this->frameworkDisk = $disk;
        $this->settings = new Handler();
        $this->projectsPath = $this->settings->getSettingsAsArray()['project_dir'];
        $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $this->jewel = new Jewel();
    }

    public function setProperties($name = 'blog', $path = null, $type = null): void
    {
        $this->name = $name;
        $this->path = is_null($path) ? Storage::disk('projects')->makeDirectory($this->frameworkDisk) : $path;
        $this->type = null;
    }

    public function setComposerPackage($package, $attributes = null): void
    {
        $this->package = $package;
    }

    public function install(): void
    {
        if (is_bool($this->path)) {
            try {
                $this->mainPath = Storage::disk('projects')->path($this->frameworkDisk);
            } catch (\Exception $e) {
                $this->mainPath = '/sdcard/www';
            }
        } else {
            $this->mainPath = $this->path;
        }
        $cmd = "cd {$this->mainPath} && composer create-project --prefer-dist {$this->package} '{$this->name}'";
        exec($cmd);

        $this->runTasks();
    }

    public function checkIfProjectExists()
    {
        if (is_bool($this->path)) {
            if (Storage::disk('projects')->exists($this->frameworkDisk.'/'.$this->name)) {
                return true;
            }
            return false;
        }
        if (is_dir($this->path .'/'. $this->name)) {
            return true;
        }
        return false;
    }

    private function runTasks(): void
    {
        $this->task('Creating directory', function () {
            return $this->jewel->createDirectory($this->mainPath . '/' . $this->name);
        });
    }
}
