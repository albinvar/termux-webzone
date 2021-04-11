<?php

namespace App\Commands\Settings;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class Settings extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'settings';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Settings for webzone';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->callSilently('settings:init');
        $this->showSettings();
    }
    
    public function logo()
    {
        $figlet = new \Laminas\Text\Figlet\Figlet();
        echo $figlet->setFont(config('logo.font'))->render(config('logo.name'));
    }
    
    public function showSettings()
    {
        echo exec('clear');
        $this->logo();
        $this->showList();
    }
    
    public function getOptions()
    {
        $json_object = file_get_contents(config('settings.PATH').'/settings.json');
        $data = json_decode($json_object, true);
        return $data;
    }
    
    public function options()
    {
        $data = $this->getOptions();
    }
    
    private function showList()
    {
        $source = $this->choice(
            'What would you like to modify',
            [1 => 'Project Root', 'Localhost Port', 'MySql Port', 'Ngrok Port', 'Tor Server Port', 'PhpMyAdmin Port', 9 => 'Exit']
        );
        switch ($source) {
            case 'Project Root':
                $body = "Project Root";
                $key = 'project_dir';
                $this->showDefault("Do you want to change default project root?", $key);
                $path = $this->dirUpdater();
                if (!$this->checkDir($path)) {
                    $this->error('The path you have provided seems to be invalid. Try again..');
                    sleep(4);
                    exec('clear');
                    return $this->call('settings');
                }
                $type = "normal";
                $this->edit($body, $key, $path, $type);
                break;
                
            case 'Localhost Port':
                $body = "Localhost Port";
                $key = 'php_port';
                $this->showDefault("Do you want to change the default Localhost Port?", $key);
                $port = $this->portUpdater();
                
                if (strlen($port) > 5) {
                    $this->error('The port you have provided seems to be invalid. Try again..');
                    sleep(3);
                    exec('clear');
                    return $this->call('settings');
                }
                $type = "normal";
                $this->edit($body, $key, $port, $type);
                break;
                
            case 'MySql Port':
                $body = "Mysql Port";
                $key = 'mysql_port';
                $this->showDefault("Do you want to change the default MySql port?", $key);
                $port = $this->portUpdater();
                
                if (strlen($port) > 5) {
                    $this->error('The port you have provided seems to be invalid. Try again..');
                    sleep(3);
                    exec('clear');
                    return $this->call('settings');
                }
                $type = "normal";
                $this->edit($body, $key, $port, $type);
                break;
                
            case 'Ngrok Port':
                $body = "Ngrok Port";
                $key = 'ngrok_port';
                $this->showDefault("Do you want to change the default Ngrok port?", $key);
                $port = $this->portUpdater();
                
                if (strlen($port) > 5) {
                    $this->error('The port you have provided seems to be invalid. Try again..');
                    sleep(3);
                    exec('clear');
                    return $this->call('settings');
                }
                $type = "normal";
                $this->edit($body, $key, $port, $type);
                break;
                
            case 'Tor Server Port':
                $body = "Tor server Port";
                $key = 'tor_port';
                $this->showDefault("Do you want to change the default Torrc port?", $key);
                $port = $this->portUpdater();
                
                if (strlen($port) > 5) {
                    $this->error('The port you have provided seems to be invalid. Try again..');
                    sleep(3);
                    exec('clear');
                    return $this->call('settings');
                }
                $type = "tor";
                $this->edit($body, $key, $port, $type);
                
                break;
                
            case 'PhpMyAdmin Port':
                $body = "PhpMyAdmin Port";
                $key = 'pma_port';
                $this->showDefault('Do you want to change the PMA port', $key);
                $port = $this->portUpdater();
                
                if (strlen($port) > 5) {
                    $this->error('The port you have provided seems to be invalid. Try again..');
                    sleep(3);
                    exec('clear');
                    return $this->call('settings');
                }
                $type = "normal";
                $this->edit($body, $key, $port, $type);
                break;

            case 'Exit':
                $this->info('Good Bye!');
                die();
                break;
        }
    }
    
    private function edit($description, $key, $default, $type="normal")
    {
        $data = $this->getOptions();
        $data[$key] = $default;
        $this->update($data, $type);
    }
    
    private function update($data, $type)
    {
        $json_object = json_encode($data);
        file_put_contents(config('settings.PATH').'/settings.json', $json_object);
        $this->comment('Updated successfully...');
        sleep(3);
        if ($type == "tor") {
            return $this->call('tor:reset');
        }
        return $this->call('settings');
    }
    
    private function checkDir($path)
    {
        if (is_dir($path)) {
            return true;
        } else {
            return false;
        }
    }
    
    private function showDefault($q="Do you want to change this setting?", $key=null)
    {
        echo exec('clear');
        $data = $this->getOptions();
        $value = $data[$key];
        $this->logo();
        $this->info('Default Value : ' . $value);
        $this->newLine();
        if ($this->confirm($q)) {
            return true;
        } else {
            return $this->call('settings');
        }
    }
    
    private function dirUpdater($q="Enter folder path")
    {
        echo exec('clear');
        $this->logo();
        $this->newLine();
        $path =  $this->askValid(
            $q,
            "path",
            ['required', 'string']
        );
        return $path;
    }
    
    private function portUpdater($q="Enter a specific port")
    {
        echo exec('clear');
        $this->logo();
        $this->newLine();
        $port = $this->askValid(
            $q,
            "port",
            ['required', 'digits_between:4,5', 'numeric']
        );
        return $port;
    }
    
    
    protected function askValid($question, $field, $rules)
    {
        $value = $this->ask($question);

        if ($message = $this->validateInput($rules, $field, $value)) {
            switch ($message) {
        case 'validation.digits_between':
            $msg = "Doesn't apper to be a valid port";
        break;
        case 'validation.required':
            $msg = "The field is required";
        break;
        case 'validation.numeric':
            $msg = "The value should be a Number";
        break;
        }
            $this->error($msg);
            return $this->askValid($question, $field, $rules);
        }

        return $value;
    }


    protected function validateInput($rules, $fieldName, $value)
    {
        $validator = Validator::make([
       $fieldName => $value
    ], [
       $fieldName => $rules
    ]);

        return $validator->fails()
        ? $validator->errors()->first($fieldName)
        : null;
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
