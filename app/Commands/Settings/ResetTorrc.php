<?php

namespace App\Commands\Settings;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ResetTorrc extends Command
{
    protected $torrc;

    protected $torrcLink;

    protected $torHiddenDir;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'tor:reset
							{--f|--force}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Reset torrc';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->callSilently('settings:init');
        $this->torrc = config('pma.TORRC');
        $this->torrcLink = config('pma.TORRC_DOWNLOAD_LINK');
        $this->torHiddenDir = config('pma.torHiddenDir');
        $this->runTasks();
        if ($this->option('force')) {
            $this->call('share:tor');
        }
    }

    private function runTasks()
    {
        // Task 1
        $this->task("Removing old torrc", function () {
            if (file_exists($this->torrc)) {
                unlink($this->torrc);
                return true;
            } else {
                return false;
            }
        });

        // Task 2
        $this->task("Downloading torrc from server", function () {
            $this->downloadCurl();
        });

        // Task 3
        $this->task("Creating required folders ", function () {
            exec("mkdir -p {$this->torHiddenDir}");
            return true;
        });
    }

    private function downloadCurl()
    {
        $lines = shell_exec("curl -w '\n%{http_code}\n' {$this->torrcLink} -o {$this->torrc}");
        $lines = explode("\n", trim($lines));
        $status = $lines[count($lines) - 1];
        $this->checkDownloadStatus($status);
    }


    private function checkDownloadStatus(int $status)
    {
        $bool = null;
        switch ($status) {
            case 000:
                $this->error("Cannot connect to the server");
                $bool = 0;
                break;
            case 200:
                $this->comment("\nDownload completed");
                $bool = 1;
                break;
            case 404:
                $this->error("File could not be found");
                $bool = 0;
                break;
            default:
                $this->error("An Unknown error occurred");
                $bool = 0;
        }
        return $bool;
    }


    /**
     * Define the command's schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
