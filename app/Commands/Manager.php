<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Laminas\Text\Figlet\Figlet;
use LaravelZero\Framework\Commands\Command;

class Manager extends Command
{
    protected $fileName;

    protected $link;

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

    /**
     * Execute the console command.
     */
    public function handle(): mixed
    {
        $this->fileName = 'index.php';
        $this->link = config('pma.MANAGER_DOWNLOAD_LINK');
        $this->manager = config('pma.MANAGER_PATH');
        pcntl_async_signals(true);
        // Catch the cancellation of the action
        pcntl_signal(SIGINT, function (): void {
            $this->newline();
            $this->comment("\nShutting down...\n");
        });
        $this->checkInstallation();
    }

    public function logo(): void
    {
        $figlet = new Figlet();
        $this->comment($figlet->setFont(config('logo.font'))->render(config('logo.name')));
    }

    public function install(): void
    {
        $link = config('pma.MANAGER_DOWNLOAD_LINK');
        $lines = shell_exec("curl -w '\n%{http_code}\n' {$link} -o {$this->manager}/{$this->fileName} && chmod +x {$this->manager}/{$this->fileName}");
        $lines = explode("\n", trim($lines));
        $status = $lines[count($lines) - 1];
        $this->checkDownloadStatus($status);
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

    private function checkInstallation(): void
    {
        if (file_exists($this->manager) && file_exists($this->manager . '/' . $this->fileName) && ! $this->option('force')) {
            $this->start();
        } else {
            $this->task('Creating Required Folders', function (): void {
                exec("mkdir -p {$this->manager}");
            });

            $this->task('Creating Required Files', function (): void {
                $this->install();
            });
            $this->start();
        }
    }

    private function start(): void
    {
        $this->line(exec('clear'));
        $this->logo();
        $this->info('Starting Temrux Manager....');
        $this->newline();
        $this->comment(exec("cd {$this->manager} && xdg-open http://127.0.0.1:9876/ && php -S 127.0.0.1:9876"));
    }

    private function checkDownloadStatus($status): void
    {
        switch ($status) {
            case 000:
                $this->error('Cannot connect to Server');
                break;
            case 200:
                $this->comment("\nDownloaded Successfully...!!!");

                break;
            case 404:
                $this->error('File not found on server..');
                break;
            default:
                $this->error('An Unknown Error occurred...');
        }
    }
}
