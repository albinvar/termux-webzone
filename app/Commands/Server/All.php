<?php

declare(strict_types=1);

namespace App\Commands\Server;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class All extends Command
{
    protected $root;

    protected $mysql;

    protected $pma;

    protected $mysqlPort;

    protected $pmaPort;

    protected $localhostPort;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'server:all';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Enable Localhost, Pma, Mysql just in one command (BETA)';

    /**
     * Execute the console command.
     */
    public function handle(): mixed
    {
        $this->callSilently('settings:init');
        $this->root = $this->getData()['project_dir'];
        $this->mysql = '/data/data/com.termux/files/usr/bin/mysql';
        $this->pma = config('pma.PMA_DIR');
        $this->mysqlPort = $this->getData()['mysqlPort'];
        $this->pmaPort = $this->getData()['pmaPort'];
        $this->localhostPort = $this->getData()['php_port'];
        $this->checkInstallation();
    }

    public function getData()
    {
        $json_object = file_get_contents(config('settings.PATH') . '/settings.json');
        return json_decode($json_object, true);
    }

    public function checkInstallation(): void
    {
        if (is_dir($this->root)) {
            
        } else {
            $this->error('The path seems to be invalid.');
            die;
        }

        if (file_exists($this->mysql)) {
            
        } else {
            $this->error('Mysql not installed yet..');
            die;
        }

        if (is_dir($this->pma)) {
            
        } else {
            $this->error('Pma not installed yet..');
            die;
        }
        $this->runAll();
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    private function runAll(): void
    {
        $cmd = "php -S 127.0.0.1:{$this->pmaPort} -t {$this->pma} & php -S 127.0.0.1:{$this->localhostPort} -t {$this->root} & mysqld --port={$this->mysqlPort} --gdb & echo \"PHP, MySQL Services Started\" && fg";
        exec($cmd);
    }
}
