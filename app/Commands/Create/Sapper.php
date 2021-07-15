<?php

declare(strict_types=1);

namespace App\Commands\Create;

use Illuminate\Console\Scheduling\Schedule;
use Laminas\Text\Figlet\Figlet;
use LaravelZero\Framework\Commands\Command;

class Sapper extends Command
{
    protected $path;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'create:sapper
							{--path=/data/data/com.termux/files/home}
							{--name=example}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'create a sapper project';

    /**
     * Execute the console command.
     */
    public function handle(): mixed
    {
        $this->path = $this->option('path');
        $this->name = $this->option('name');
        $this->call('nodejs:init');
        echo exec('clear');
        $this->logo();
        $this->newLine();
        $this->comment("Creating Sapper Project {$this->name} on {$this->path}");
        $this->info("npm won't work on /sdcard unless you need to root you phone. You can create Projects on termux home to use npm.");
        $this->newLine();
        $this->install();
        $this->create();
    }

    public function logo(): void
    {
        $figlet = new Figlet();
        $this->info($figlet->setFont(config('logo.font'))->render('Sapper'));
    }

    public function install(): void
    {
        $code = exec('npm install -g degit');
    }

    public function create(): void
    {
        exec('cd ' . $this->path . ' && npx degit "sveltejs/sapper-template#rollup" ' . $this->name . ' && cd ' . $this->name . ' && npm install && npm run dev');
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
