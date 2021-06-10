<?php

namespace App\Commands\Create;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Symfony extends Command
{
    protected $dir;
    
    protected $path;
    
    protected $type;
    
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'create:symfony
							{name?}
							{--type=}
							{--path=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create symfony projects';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->callSilently('settings:init');
        $this->dir = $this->getData()['project_dir'];
        $this->line(exec('clear'));
        $this->logo();
        $this->init();
    }
    
    private function init()
    {
        //name of project
        if (!empty($this->argument('name'))) {
            $this->name = $this->argument('name');
        } else {
            //planing to generate random names from a new package.
            $this->name = 'symfony_project';
        }
    
        //set path
        if (!empty($this->option('path'))) {
            $this->path = $this->option('path');
        } elseif (!empty($this->dir) && is_dir($this->dir)) {
            $this->path = $this->dir;
        } else {
            $this->path = '/sdcard';
        }
        
        // set project type.
        if (!empty($this->option('type'))) {
            if (in_array($this->option('type'), config('symfony.TYPES'))) {
                if ($this->option('type') == 'web') {
                    $this->type = 'symfony/website-skeleton';
                } elseif ($this->option('type') == 'console') {
                    $this->type = 'symfony/skeleton';
                } else {
                    $this->type = 'symfony/skeleton';
                }
            } else {
                $this->error('Invalid type');
                die();
            }
        } else {
            $this->type = 'symfony/website-skeleton';
        }
            
        
        //check if directory exists
        if (!$this->checkDir()) {
            exit();
        } else {
            $this->line(exec('tput sgr0'));
            $this->info('Creating Symfony app');
            $this->newline();
            $this->create();
            $this->newline();
            $this->comment("Symfony App created successfully on {$this->path}/{$this->name}");
        }
    }
    
    private function create()
    {
        $cmd = "cd {$this->path} && composer create-project {$this->type} \"{$this->name}\"";
        $this->exec($cmd);
    }
    
    private function exec($command)
    {
        $this->line(exec($command));
    }
    
    public function logo()
    {
        $figlet = new \Laminas\Text\Figlet\Figlet();
        $this->comment($figlet->setFont(config('logo.font'))->render("Symfony"));
    }
    
    private function checkDir()
    {
        if (file_exists($this->path . '/' . $this->name)) {
            $this->error("A duplicate file/directory found in the path. Please choose a better name.");
            return false;
        } else {
            return true;
        }
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
