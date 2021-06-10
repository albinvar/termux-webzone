<?php

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
     *
     * @return mixed
     */
    public function handle()
    {
        $this->callSilently('settings:init');
        $this->root = $this->getData()['project_dir'];
        $this->mysql = "/data/data/com.termux/files/usr/bin/mysql";
        $this->pma = config('pma.PMA_DIR');
        $this->mysqlPort = $this->getData()['mysqlPort'];
        $this->pmaPort = $this->getData()['pmaPort'];
        $this->localhostPort = $this->getData()['php_port'];
        $this->checkInstallation();
    }
    
    public function checkInstallation()
    {
        if (is_dir($this->root)) {
            //
        } else {
            $this->error('The path seems to be invalid.');
            die();
        }
    
        if (file_exists($this->mysql)) {
            //
        } else {
            $this->error('Mysql not installed yet..');
            die();
        }
    
        if (is_dir($this->pma)) {
            //
        } else {
            $this->error('Pma not installed yet..');
            die();
        }
        $this->runAll();
    }
    
    private function runAll()
    {
        $cmd = "php -S 127.0.0.1:{$this->pmaPort} -t {$this->pma} & php -S 127.0.0.1:{$this->localhostPort} -t {$this->root} & mysqld --port={$this->mysqlPort} --gdb & echo \"PHP, MySQL Services Started\" && fg";
        exec($cmd);
    }
    
    public function getData()
    {
        $json_object = file_get_contents(config('settings.PATH').'/settings.json');
        $data = json_decode($json_object, true);
        return $data;
    }
    
    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
