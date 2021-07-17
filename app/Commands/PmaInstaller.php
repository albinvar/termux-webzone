<?php

declare(strict_types=1);

namespace App\Commands;

use App\Helpers\Downloader;
use App\Helpers\PhpMyAdmin;
use App\Helpers\Webzone;
use App\Helpers\Zipper;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

class PmaInstaller extends Command
{
    protected $dir;

    protected $downloader;

    protected $pma;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'install:pma
							{--fresh}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Install PhpMyAdmin web interface';

    public function __construct()
    {
        parent::__construct();

        $this->dir = config('pma.PMA_DIR');

        $this->pma = new PhpMyAdmin();

        $this->webzone = new Webzone();

        $this->pmaData = $this->pma->latestRelease();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->callSilently('settings:init');

        $this->checkInstallation();
    }

    public function checkInstallation(): void
    {
        $this->webzone->clear();
        $this->newline();
        $this->webzone->logo();
        $this->newline();
        if (is_dir($this->dir . '/www') && file_exists($this->dir . '/www/config.inc.php')) {
            if ($this->confirm('Do you want to reinstall PMA?')) {
                $this->showLatestRelease();
            }
        } else {
            $this->showLatestRelease();
        }
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    private function showLatestRelease()
    {
        if (! $this->pmaData) {
            $this->error("Couldn't connect to server.");
            return 1;
        }

        $headers = ['Name', 'Version', 'Released on'];

        $data = [];
        $versions = [];

        foreach ($this->pmaData['releases'] as $release) {
            $label = $release['version'] === $this->pmaData['version'] ? ' (latest)' : null;
            $data[] = ['PhpMyAdmin', $release['version'] . $label, $release['date']];
            $versions[] = $release['version'];
        }

        $this->table($headers, $data);

        $this->version = $this->choice(
            'Which version would you like to use?',
            $versions
        );

        $this->runTasks();
    }

    private function removeInstalledPhpMyAdmin(): void
    {
        $this->task('Removing Old Files', function (): void {
            $this->pma->removeOld();
        });
    }

    private function createDirectory(): void
    {
        $this->task('Creating Required Folders ', function () {
            if (! File::isDirectory($this->dir)) {
                try {
                    File::makeDirectory($this->dir, 0777, true, true);
                    return true;
                } catch (\Exception $e) {
                    return false;
                }
            }
            return true;
        });
    }

    private function getUrl()
    {
        if (! isset($this->version)) {
            return false;
        }
        return 'https://files.phpmyadmin.net/phpMyAdmin/'.$this->version.'/phpMyAdmin-'.$this->version.'-all-languages.zip';
        //return 'http://127.0.0.1:8999/phpMyAdmin-5.1.1-all-languages.zip';
    }

    private function download(): void
    {
        $downloadTask = $this->task('Downloading resources ', function () {
            $this->downloader = new Downloader($this->getUrl(), 'phpMyAdmin-v' . $this->version . '.zip');
            $response = $this->downloader->download();

            if ($response['ok']) {
                return true;
            }
            $this->error($response['error']->getMessage());
            return false;

        
        });
    }

    private function runTasks(): void
    {
        if ($this->option('fresh')) {
            $this->removeInstalledPhpMyAdmin();
        }

        $this->createDirectory();

        $this->task('Setting url ', function () {
            return $this->getUrl();
        });

        $this->download();

        $this->task('Extracting Zip ', function () {
            $zip = new Zipper($this->dir, $this->dir.'/tmp/phpMyAdmin-v' . $this->version . '.zip', $this->dir.'/www');
            return $zip->unzip() ? true : false;
        });

        $this->task('Set Configuration File ', function () {
            if ($this->pma->configurator('/www/phpMyAdmin-' . $this->version . '-all-languages', 'config.sample.inc.php')) {
                return true;
            }
            return false;
        });

        $this->task('Removing downloaded files ', function () {
            if ($this->downloader->clean()) {
                return true;
            }
            return false;
        });

        $this->task('Setting PhpMyAdmin root ', function () {
            if ($this->pma->updateRoot(\Storage::disk('local')->getAdapter()->getPathPrefix().'/www/phpMyAdmin-' . $this->version . '-all-languages')) {
                return true;
            }
            return false;
        });
    }
}
