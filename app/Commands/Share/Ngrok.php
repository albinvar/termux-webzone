<?php

declare(strict_types=1);

namespace App\Commands\Share;

use Illuminate\Console\Scheduling\Schedule;
use Laminas\Text\Figlet\Figlet;
use LaravelZero\Framework\Commands\Command;

class Ngrok extends Command
{
    protected $dir;

    protected $port;

    protected $ngrok;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'share:ngrok
							{--s|--stop}
							{--port=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'portforward through ngrok';

    /**
     * Execute the console command.
     */
    public function handle(): mixed
    {
        $this->callSilently('settings:init');
        $this->dir = '/data/data/com.termux/files/usr/bin';
        $this->ngrok = config('pma.NGROK');
        $this->setPort();

        if ($this->option('stop')) {
            exec('killall ngrok -q');
            $this->comment('killed all ngrok sessions..');
            exit;
        }
        echo exec('clear');
        $this->checkInstallation();
    }

    public function getPort()
    {
        $json_object = file_get_contents(config('settings.PATH') . '/settings.json');
        $data = json_decode($json_object, true);
        return $data['ngrok_port'];
    }

    public function checkInstallation()
    {
        $this->logo();
        if (! file_exists($this->dir . '/jq')) {
            if ($this->confirm('Do you want to install jq?')) {
                $this->installjq();
                sleep(1);
                $this->call('share:ngrok');
            } else {
                $this->error('aborting...');
                exit;
            }
        }
        if (file_exists($this->dir . '/ngrok')) {
            $this->activity();
            return true;
        }
        if ($this->confirm('Do you want to install ngrok?')) {
            $this->installngrok();
            sleep(1);
            $this->call('share:ngrok');
        } else {
            $this->error('aborting...');
        }
    }

    public function logo(): void
    {
        $figlet = new Figlet();
        $this->comment($figlet->setFont(config('logo.font'))->render(config('logo.name')));
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
            $this->port = config('pma.NGROK_PORT');
        }
    }

    private function installjq(): void
    {
        $this->task('Installing jq', function (): void {
            $cmd = 'apt-get install jq -y -qqq';
            exec($cmd);
        });
    }

    private function activity(): void
    {
        $this->info('Starting ngrok....');
        exec('killall ngrok -q');
        exec("ngrok http {$this->port} > /dev/null 2>/dev/null &");
        sleep(3);
        //pclose(popen("ngrok http {$this->option('port')}","r"));
        $this->newLine();
        $this->getUrl();
    }

    private function getUrl()
    {
        $cmd = exec('curl -s localhost:4040/api/tunnels | jq -r .tunnels[0].public_url');

        if (! empty($cmd) && $cmd !== 'null') {
            $this->comment('Tunnel created successfully.');
            $this->newLine();
            $this->info('Link : ' . $cmd);
            $this->newLine();
            $this->comment('use -s or --stop to kill ngrok sessions');
            return true;
        }
        $this->error('Link Generation failed. Please turn on your hotspot and try again.');
        return false;
    }

    private function installngrok(): void
    {
        $this->task('Installing ngrok', function (): void {
            $this->downloadNgrokCurl();
        });
    }

    private function downloadNgrokCurl(): void
    {
        $lines = shell_exec("curl -w '\n%{http_code}\n' {$this->ngrok} -o {$this->dir}/ngrok");
        $lines = explode("\n", trim($lines));
        $status = $lines[count($lines) - 1];
        $this->checkDownloadStatus($status, $this->dir);
    }

    private function checkDownloadStatus($status, $dir): void
    {
        switch ($status) {
            case 000:
                $this->error('Cannot connect to Server');
                break;
            case 200:
                $this->comment("\nDownloaded Successfully...!!!");
                $this->runTasks();
                break;
            case 404:
                $this->error('File not found on server..');
                break;
            default:
                $this->error('An Unknown Error occurred...');
        }
    }

    private function runTasks(): void
    {
        $this->task('Setting up permissions', function () {
            if ($this->grantPermission()) {
                return true;
            }
            return false;
        });
    }

    private function grantPermission()
    {
        if (file_exists($this->dir . '/ngrok')) {
            exec("chmod +x {$this->dir}/ngrok");
            return true;
        }
        return false;
    }
}
