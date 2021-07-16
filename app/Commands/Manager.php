<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Laminas\Text\Figlet\Figlet;
use App\Helpers\Downloader;
use Storage;
use LaravelZero\Framework\Commands\Command;

class Manager extends Command
{
    protected $fileName = 'index.php';

    protected $link;

    protected $dir = 'manager';

    protected $manager;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'manager
							{--f|--force}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Web based file manager for termux';


    public function __construct()
    {
        parent::__construct();


        $this->link = config('manager.MANAGER_DOWNLOAD_LINK');
        $this->manager = config('manager.MANAGER_PATH');
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->callSilently('settings:init');

        pcntl_async_signals(true);

        pcntl_signal(SIGINT, function (): void {
            $this->newline();
            $this->comment("\nShutting down...\n");
        });

        $this->checkInstallation();
    }

    private function start(): void
    {
        $this->line(exec('clear'));
        $this->logo();
        $this->info('Starting Webzone Manager....');
        $this->newline();
        $this->comment(exec("cd {$this->manager} && xdg-open http://127.0.0.1:9876/ && php -S 127.0.0.1:9876"));
    }

    private function checkInstallation(): void
    {
        if (file_exists($this->manager) && file_exists($this->manager . '/' . $this->fileName) && ! $this->option('force')) {
            $this->start();
        } else {
            $this->install();
            sleep(5);
            $this->start();
        }
    }

    public function logo(): void
    {
        $figlet = new Figlet();
        $this->comment($figlet->setFont(config('logo.font'))->render(config('logo.name')));
    }

    public function install(): void
    {
        $this->createDirectory();

        $this->download();

        $this->newline();

        $this->info('Installation Successful. Starting webzone manager for you...');
    }

    private function createDirectory()
    {
        $this->task('Creating Required Folders ', function () {
            try {
                Storage::makeDirectory($this->dir);
                return true;
            } catch (\Exception $e) {
                return false;
            }
        });
    }

    private function download()
    {
        $downloadTask = $this->task('Downloading resources ', function () {
            $this->downloader = new Downloader($this->link, 'manager/index.php', 'local');
            $response = $this->downloader->download();

            if ($response['ok']) {
                return true;
            } else {
                $this->error($response['error']->getMessage());
                return false;
            }
        });
    }

    public function stop(): void
    {
        $this->comment('Shutting Down...');
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
