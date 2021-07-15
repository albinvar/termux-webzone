<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Laminas\Text\Figlet\Figlet;
use LaravelZero\Framework\Commands\Command;
use App\Helpers\Downloader;
use App\Helpers\Zipper;
use App\Helpers\PhpMyAdmin;
use Illuminate\Support\Facades\File;

class Install extends Command
{
    protected $dir;

    protected $downloader;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'install:pma
							{--f|--force}';

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
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->callSilently('settings:init');
        if ($this->option('force')) {
            $this->removeDir();
        }
        $this->checkInstallation();
    }

    public function checkInstallation(): void
    {
        $this->info("\n");
        $this->logo();
        $this->info("\n");
        if (is_dir($this->dir . '/www') && file_exists($this->dir . '/www/config.inc.php')) {
            if ($this->confirm('Do you want to reinstall PMA?')) {
                $this->showLatestRelease();
            }
        } else {
            $this->showLatestRelease();
        }
    }

    private function showLatestRelease()
    {
        $pma = new PhpMyAdmin();
        $pma = $pma->latestRelease();

        if (!$pma) {
            $this->error("Couldn't connect to server.");
            return 1;
        }

        $headers = ['Name', 'Version', 'Released on'];

        $data = [];
        $versions = [];

        foreach ($pma['releases'] as $release) {
            $label = ($release['version'] === $pma['version']) ? ' (latest)' : null;
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

    public function logo(): void
    {
        $figlet = new Figlet();
        echo $figlet->setFont(config('logo.font'))->render(config('logo.name'));
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    private function removeDir(): void
    {
        $this->task("\nRemoving Old Files", function () {
            if (is_dir($this->dir . '/www')) {
                $cmd = shell_exec("rm -rf {$this->dir}/pma");
                if (is_null($cmd)) {
                    return true;
                }
                return false;
            }
            if (file_exists($this->dir . '/www/config.inc.php')) {
                $cmd = shell_exec("rm {$this->dir}/pma.zip");
                if (is_null($cmd)) {
                    return true;
                }
                return false;
            }
            return true;
        });
    }

    private function createDirectory()
    {
        $this->task('Creating Required Folders ', function () {
            if (!File::isDirectory($this->dir)) {
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
        if (!isset($this->version)) {
            return false;
        }
        //return 'https://files.phpmyadmin.net/phpMyAdmin/'.$this->version.'/phpMyAdmin-'.$this->version.'-all-languages.zip';
        return 'http://127.0.0.1:8999/phpMyAdmin-5.1.1-all-languages.zip';
    }

    private function download()
    {
        $downloadTask = $this->task('Downloading resources ', function () {
            $this->downloader = new Downloader($this->getUrl(), 'phpMyAdmin-v' . $this->version . '.zip');
            $response = $this->downloader->download();

            if ($response['ok']) {
                return true;
            } else {
                $this->error($response['error']->getMessage());
                return false;
            }
        });
    }

    private function runTasks(): void
    {
        $this->createDirectory();

        $this->task('Setting url ', function () {
            return $this->getUrl();
        });

        $this->download();

        $this->task('Extracting Zip ', function () {
            $zip = new Zipper($this->dir, $this->dir.'/tmp/phpMyAdmin-v' . $this->version . '.zip', $this->dir.'/www');
            return ($zip->unzip()) ? true : false;
        });

        $this->task('Set Configuration File ', function () {
            $pma = new PhpMyAdmin();
            if ($pma->configurator('/www/phpMyAdmin-' . $this->version . '-all-languages', 'config.sample.inc.php')) {
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
    }
}
