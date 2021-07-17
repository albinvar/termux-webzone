<?php

declare(strict_types=1);

namespace App\Commands;

use App\Helpers\Downloader;
use App\Helpers\Webzone;
use App\Helpers\WebzoneManager;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Storage;

class Manager extends Command
{
    protected $filename = 'index.php';

    protected $webzone;

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

        $this->webzone = new Webzone();
        $this->manager = new WebzoneManager();
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

    public function install(): void
    {
        $this->createDirectory();
        $this->download();
        $this->newline();
        $this->info('Installation Successful. Starting webzone manager for you...');
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

    private function start(): void
    {
        $this->info('Starting Webzone Manager....');
        $this->newline();
        $this->manager->startServer();
    }

    private function checkInstallation()
    {
        $this->webzone->clear();
        $this->webzone->logo(null, 'comment');
        if (Storage::disk('local')->exists('manager/index.php') && ! $this->option('force')) {
            $this->start();
        } else {
            if ($this->confirm('Do you want to install Manager component')) {
                $this->install();
                sleep(5);
                $this->start();
            } else {
                $this->error('Aborting...');
                return true;
            }
        }
    }

    private function createDirectory(): void
    {
        $this->task('Creating Required Folders ', function () {
            try {
                Storage::makeDirectory($this->manager->getPath());
                return true;
            } catch (\Exception $e) {
                return false;
            }
        });
    }

    private function download(): void
    {
        $downloadTask = $this->task('Downloading resources ', function () {
            $this->downloader = new Downloader($this->manager->getLink(), $this->manager->getPath().'/'.$this->filename, 'local');
            $response = $this->downloader->download();

            if ($response['ok']) {
                return true;
            }
            $this->error($response['error']->getMessage());
            return false;

        
        });
    }
}
