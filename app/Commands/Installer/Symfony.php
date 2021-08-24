<?php

declare(strict_types=1);

namespace App\Commands\Installer;

use App\Helpers\Downloader;
use App\Helpers\Webzone;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class Symfony extends Command
{
    protected static $symfony;

    protected static $dir;

    protected static $cliName = 'symfony';

    protected static $disk;

    protected static $link;

    protected static string $needle = 'Symfony CLI version';

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'installer:symfony
							{--uninstall}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Install Symfony CLI ';

    public function __construct()
    {
        parent::__construct();

        $this->webzone = new Webzone();
    }

    /**
     * Execute the console command.
     */
    public function handle(): mixed
    {
        $this->callSilently('settings:init');

        self::setConfigs();

        if ($this->checkInstallation() && ! $this->option('uninstall')) {
            $this->error('Symfony CLI is already installed. Use "symfony help" to show all commands.');
            return 1;
        }

        $this->webzone->clear();

        $this->webzone->logo('Symfony', 'comment');

        if ($this->option('uninstall')) {
            $this->uninstall();
            return 0;
        }

        $this->install();
        return 0;
    }

    public static function setConfigs(): void
    {
        static::$symfony = config('symfony.CLI_PATH');
        static::$dir = config('symfony.PATH', '/data/data/com.termux/files/usr/bin');
        static::$link = config('symfony.CLI_DOWNLOAD_LINK');
        static::createDiskInstance();
    }

    public static function createDiskInstance(): void
    {
        static::$disk = Storage::build([
            'driver' => 'local',
            'root' => static::$dir,
        ]);
    }

    public function install(): void
    {
        $this->newline();
        $this->comment('Installing Symfony CLI...');
        $this->newline();

        $this->runTasks();
    }

    public function uninstall(): void
    {
        $this->task('Uninstalling Symfony CLI ', function () {
            return static::$disk->delete(static::$cliName);
        });
    }

    public function checkInstallation(): bool
    {
        return static::$disk->has(static::$cliName);
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    private function runTasks(): void
    {
        $this->download();

        $this->task('setting permissions', function () {
            return chmod(static::$symfony, 0775);
        });

        $this->task('verifying command ', function () {
            return str_contains(shell_exec('symfony version'), static::$needle) && file_exists(static::$symfony);
        });
    }

    private function download(): void
    {
        $downloadTask = $this->task('Downloading resources ', function () {
            $this->downloader = new Downloader(static::$link, static::$cliName, static::$disk);
            $response = $this->downloader->download();

            if ($response['ok']) {
                return true;
            }

            $this->error($response['error']->getMessage());
            return false;
        });
    }
}
