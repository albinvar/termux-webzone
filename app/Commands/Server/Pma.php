<?php

declare(strict_types=1);

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
     */
    public function handle(): mixed
    {
        $this->callSilently('settings:init');
        $this->setPort();
        $this->checkInstallations();
    }

    public function getData()
    {
        $json_object = file_get_contents(config('settings.PATH') . '/settings.json');
        return json_decode($json_object, true);
    }

    public function checkInstallations(): void
    {
        echo shell_exec('clear');
        $this->info('');
        $this->logo();
        $this->info("\n");
        $dir = $this->getData()['pma_root'];
        $cmd = "php -S 127.0.0.1:{$this->port} -t {$dir}";
        $this->comment("Starting phpmyadmin web interface at : http://127.0.0.1:{$this->port}");
        $this->info('');
        $this->launch();
        shell_exec($cmd);
    }

    public function logo(): void
    {
        $figlet = new Figlet();
        echo $figlet->setFont(config('logo.font'))->render(config('logo.name'));
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    private function setPort(): void
    {
        if (! empty($this->option('port'))) {
            $this->port = $this->option('port');
        } elseif (! empty($this->getData()['pma_port'])) {
            $this->port = $this->getData()['pma_port'];
        } else {
            $this->port = config('pma.PMA_PORT');
        }
    }

    private function launch()
    {
        if (! $this->option('n')) {
            return shell_exec("xdg-open http://127.0.0.1:{$this->port}");
        }
    }
}
