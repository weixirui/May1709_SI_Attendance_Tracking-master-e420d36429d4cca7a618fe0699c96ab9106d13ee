<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

// Symphony Requirements
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class watchlog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:laravel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tail the laravel log file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // get the path to laravel.log
        $logfile = storage_path('logs/laravel.log');

        // notify user that we are about to tail
        $this->info('Starting tail on storage/logs/laravel.log'); // This is how you output to console

        // the command we want symphony to execute
        $command = 'tail -f ' . $logfile;

        // set up output piping
        $output = $this->output;

        // run the process and pipe output
        (new Process($command))->setTimeout(null)->run(function ($type, $line) use ($output) {
            $output->write($line);
        });
    }
}
