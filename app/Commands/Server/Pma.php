<?php

namespace App\Commands\Server;

use Illuminate\Console\Scheduling\Schedule;
use Laminas\Text\Figlet\Figlet;
use LaravelZero\Framework\Commands\Command;

class Pma extends Command
{
    protected $port;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'server:pma
							{--n}
							{--port=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Start PhpMyAdmin locally';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->callSilently('settings:init');
        $this->setPort();
        $this->checkInstallations();
    }

    private function setPort()
    {
        if (!empty($this->option('port'))) {
            $this->port = $this->option('port');
        } elseif (!empty($this->getPort())) {
            $this->port = $this->getPort();
        } else {
            $this->port = config('pma.PMA_PORT');
        }
    }

    public function getPort()
    {
        $json_object = file_get_contents(config('settings.PATH') . '/settings.json');
        $data = json_decode($json_object, true);
        return $data['pma_port'];
    }

    public function checkInstallations()
    {
        echo shell_exec("clear");
        $this->info("");
        $this->logo();
        $this->info("\n");
        $dir = config('pma.PMA_DIR');
        $cmd = "php -S 127.0.0.1:{$this->port} -t {$dir}/pma";
        $this->comment("Starting phpmyadmin web interface at : http://127.0.0.1:{$this->port}");
        $this->info("");
        $this->launch();
        shell_exec($cmd);
    }

    public function logo()
    {
        $figlet = new Figlet();
        echo $figlet->setFont(config('logo.font'))->render(config('logo.name'));
    }

    private function launch()
    {
        if (!$this->option('n')) {
            return shell_exec("xdg-open http://127.0.0.1:{$this->port}");
        }
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
