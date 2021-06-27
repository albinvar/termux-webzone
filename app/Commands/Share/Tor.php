<?php

declare(strict_types=1);

namespace App\Commands\Share;

use Illuminate\Console\Scheduling\Schedule;
use Laminas\Text\Figlet\Figlet;
use LaravelZero\Framework\Commands\Command;

class Tor extends Command
{
    protected $torrc;

    protected $dir;

    protected $port;

    protected $olds;

    protected $old1;

    protected $old2;

    protected $string1;

    protected $string2;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'share:tor
							{--reset}
							{--port=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'portforward through tor';

    /**
     * Execute the console command.
     */
    public function handle(): mixed
    {
        $this->torrc = config('pma.TORRC');
        $this->dir = '/data/data/com.termux/files/usr/bin';
        if (! file_exists($this->torrc)) {
            $this->callSilently('tor:reset');
        }
        echo exec('clear');
        $this->setPort();
        if ($this->option('reset')) {
            $this->call('tor:reset', ['--force' => true]);
            exit;
        }

        $this->checkInstallation();
    }

    public function getPort()
    {
        $json_object = file_get_contents(config('settings.PATH') . '/settings.json');
        $data = json_decode($json_object, true);
        return $data['tor_port'];
    }

    public function checkInstallation(): void
    {
        $this->logo();
        if (! file_exists($this->dir . '/tor')) {
            if ($this->confirm('Do you want to install tor?')) {
                $this->installTor();
                sleep(1);
                $this->call('share:tor');
            } else {
                $this->error('aborting...');
                exit;
            }
        } else {
            $this->olds = $this->setString();
            foreach ($this->olds as $string) {
                $this->checkIfInitialized($string['old'], $string['new'], $string['type']);
            }
            $this->comment('Starting up Tor client.....');
            exec('killall tor >/dev/null 2>&1');
            $this->line(exec('tor > /dev/null 2>/dev/null &'));
            sleep(3);
            $this->info($this->getHostname());
        }
    }

    public function logo(): void
    {
        $figlet = new Figlet();
        $this->comment($figlet->setFont(config('logo.font'))->render(config('logo.name')));
    }

    public function setString()
    {
        $this->old1 = "\nHiddenServiceDir";
        $this->old2 = "\nHiddenServicePort";
        $this->string1 = "\nHiddenServiceDir /data/data/com.termux/files/usr/var/lib/tor/hidden_service/";
        $this->string2 = "\nHiddenServicePort 80 127.0.0.1:{$this->port}";
        return [['old' => $this->old1, 'new' => $this->string1, 'type' => 'hidden service directory'], ['old' => $this->old2, 'new' => $this->string2, 'type' => 'hidden service port']];
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
            $this->port = config('pma.TOR_PORT');
        }
    }

    private function installTor(): void
    {
        $this->task('Installing tor', function (): void {
            $cmd = 'apt-get install tor -y -qqq';
            exec($cmd);
        });
    }

    private function checkIfInitialized($old, $new, $type): void
    {
        $file = file_get_contents($this->torrc);

        if (strpos(file_get_contents($this->torrc), $old) !== false) {
            $this->comment("\nPreapplied settings already found. Skipping...");
        } else {
            $is_initiated = $this->task("configuring {$type} ", function () use (&$new) {
                if ($this->rewrite($new)) {
                    return true;
                }
                return false;

            
            });

            if ($is_initiated) {
                $this->info("\n{$type} initialised successfully..\n");
            }
        }
        $this->newLine();
    }

    private function rewrite($string)
    {
        $action = file_put_contents($this->torrc, $string, FILE_APPEND | LOCK_EX);
        sleep(1);
        if ($action) {
            return true;
        }
        return false;

    
    }

    private function getHostname()
    {
        $file = '/data/data/com.termux/files/usr/var/lib/tor/hidden_service/hostname';
        if (file_exists($file)) {
            $link = file_get_contents($file);
            return 'link : ' . $link;
        }
        return 'Something went wrong....';

    
    }
}
