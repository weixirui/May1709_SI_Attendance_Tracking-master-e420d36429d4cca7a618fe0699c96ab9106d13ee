<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class QueueReplace extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reboot the queue and start a worker';

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
        // first restart the queue
        $this->call('queue:restart');

        // update the user
        $this->info('Worker waiting for jobs ...');

        // then start it up
        $this->call('queue:work', [
            '--tries' => '1'
        ]);
    }
}
