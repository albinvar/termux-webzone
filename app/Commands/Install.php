<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Laminas\Text\Figlet\Figlet;
use LaravelZero\Framework\Commands\Command;
use ZipArchive;

class Install extends Command
{
    protected $dir;

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
    public function handle(): mixed
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
        if (is_dir($this->dir . '/pma') && file_exists($this->dir . '/pma/config.inc.php')) {
            if ($this->confirm('Do you want to reinstall PMA?')) {
                $this->createDirectory();
            }
        } else {
            $this->createDirectory();
        }
    }

    /*
    private function downloadPMA()
    {
        $url = "https://mattstauffer.com/assets/images/logo.svg";
        $fp = fopen($dir . 'name.zip', "w+");

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_exec($ch);
        $st_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        fclose($fp);

        if($st_code == 200)
             $this->info('File downloaded successfully!');
        else {
             $this->error('Error downloading file!');
        }

    }
    */

    public function logo(): void
    {
        $figlet = new Figlet();
        echo $figlet->setFont(config('logo.font'))->render(config('logo.name'));
    }

    public function isSiteAvailable($url)
    {
        // Check, if a valid url is provided
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Initialize cURL
        $curlInit = curl_init($url);

        // Set options
        curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curlInit, CURLOPT_HEADER, true);
        curl_setopt($curlInit, CURLOPT_NOBODY, true);
        curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);

        // Get response
        $response = curl_exec($curlInit);

        // Close a cURL session
        curl_close($curlInit);

        return $response ? true : false;
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    protected function getUrl(): void
    {
        $status = $this->isSiteAvailable(config('pma.PMA_URL'));
        if ($status) {
            $json = file_get_contents(config('pma.PMA_URL'));
            $data = json_decode($json, true);
            $this->downloadPMACurl($data);
        } else {
            $data = ['PMA_DOWNLOAD_LINK' => config('pma.PMA_DEFAULT_DOWNLOAD_LINK')];
            $this->downloadPMACurl($data);
        }
    }

    private function removeDir(): void
    {
        $this->task("\nRemoving Old Files", function () {
            if (is_dir($this->dir . '/pma')) {
                $cmd = shell_exec("rm -rf {$this->dir}/pma");
                if (is_null($cmd)) {
                    return true;
                }
                return false;
            }
            if (file_exists($this->dir . '/pma/config.inc.php')) {
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
        if (! is_dir($this->dir)) {
            mkdir($this->dir);
            $this->info('Directory created successfully..');
        }
        return $this->getUrl();
    }

    private function downloadPMACurl($data): void
    {
        $lines = shell_exec("curl -w '\n%{http_code}\n' {$data['PMA_DOWNLOAD_LINK']} -o {$this->dir}/pma.zip");
        $lines = explode("\n", trim($lines));
        $status = $lines[count($lines) - 1];
        $this->checkDownloadStatus($status);
    }

    private function checkDownloadStatus($status): void
    {
        switch ($status) {
            case 000:
                $this->error('Cannot connect to Server');
                break;
            case 200:
                $this->comment("\nDownloaded Successfully...!!!");
                $this->runTasks();
                break;
            case 404:
                $this->error('File not found on server..');
                break;
            default:
                $this->error('An Unknown Error occurred...');
        }
    }

    private function runTasks(): void
    {
        $this->task('Extracting PMA ', function () {
            if ($this->unzip()) {
                return true;
            }
            return false;

        
        });
        $this->task('Set Configuration File ', function () {
            if ($this->setPmaConfig()) {
                return true;
            }
            return false;

        
        });
    }

    private function unzip()
    {
        $zip = new ZipArchive();
        $file = $this->dir . '/pma.zip';

        // open archive
        if ($zip->open($file) !== true) {
            return false;
        }
        // extract contents to destination directory
        $zip->extractTo($this->dir . '/pma');
        // close archive
        $zip->close();
        return true;
    }

    private function setPmaConfig()
    {
        if (file_exists($this->dir . '/pma/config.sample.inc.php')) {
            if (@rename($this->dir . '/pma/config.sample.inc.php', $this->dir . '/pma/config.inc.php') === true) {
                return true;
            }
            return false;

        
        }
    }
}
