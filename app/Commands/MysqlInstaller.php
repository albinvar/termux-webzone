<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class MysqlInstaller extends Command
{
    protected $mysql;

    protected $command;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'install:mysql';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Install mysql server on Termux';

    /**
     * Execute the console command.
     */
    public function handle(): mixed
    {
        $this->callSilently('settings:init');
        $this->mysql = config('pma.MYSQL_PATH');
        $this->command = config('pma.MYSQL_INSTALLATION_COMMAND');
        $this->checkInstallation();
    }

    public function checkInstallation(): void
    {
        if (file_exists($this->mysql)) {
            $this->error('Mysql seems to be installed already, Type "pkg uninstall mariadb" to uninstall mysql..');
        } else {
            if ($this->confirm('Do you want to install MySql?')) {
                $this->install();
            }
        }
    }

    /**
     * Define the command's itschedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    private function install(): void
    {
        $this->info("\nInstalling MySql\n");

        exec($this->command, $output, $result);

        $this->comment('MySql installed successfully...');
    }
}
