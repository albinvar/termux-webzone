<?php

declare(strict_types=1);

namespace App\Commands\Server;

use Illuminate\Console\Scheduling\Schedule;
use Laminas\Text\Figlet\Figlet;
use LaravelZero\Framework\Commands\Command;

class Wordpress extends Command
{
    protected $port;

    protected $wordpress;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'server:wordpress
							{--n}
							{--port=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Start wordpress site';

    /**
     * Execute the console command.
     */
    public function handle(): mixed
    {
        $this->callSilently('settings:init');
        $this->wordpress = config('wordpress.PATH');
        $this->setPort();
        $this->install();
    }

    public function getPort()
    {
        $json_object = file_get_contents(config('settings.PATH') . '/settings.json');
        $data = json_decode($json_object, true);
        return $data['wordpress_port'];
    }

    public function install()
    {
        echo shell_exec('clear');
        $this->info('');
        $this->logo();
        $this->info("\n");
        $status = $this->task('Checking Installations', function () {
            return $this->checkInstallation();
        });

        if (! $status) {
            if ($this->confirm('Do you want Install wordpress?')) {
                $this->call('installer:wordpress');
            } else {
                $this->error('Exiting...');
                return true;
            }
        }

        $dir = config('wordpress.PATH');
        $cmd = "php -S 127.0.0.1:{$this->port} -t {$dir}";
        $this->comment("\nStarting wordpress at : http://127.0.0.1:{$this->port}");
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
        } elseif (! empty($this->getPort())) {
            $this->port = $this->getPort();
        } else {
            $this->port = config('WORDPRESS_PORT');
        }
    }

    private function checkInstallation()
    {
        if (is_dir($this->wordpress) && file_exists($this->wordpress . '/readme.html')) {
            return true;
        }
        return false;
    }

    private function launch()
    {
        if (! $this->option('n')) {
            return shell_exec("xdg-open http://127.0.0.1:{$this->port}");
        }
    }
}
