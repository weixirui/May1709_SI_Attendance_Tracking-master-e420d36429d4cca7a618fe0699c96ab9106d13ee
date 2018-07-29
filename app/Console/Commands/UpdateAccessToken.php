<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ApiHelper;

class UpdateAccessToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'box:updateToken';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the Box access token in the database';

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
        ApiHelper::updateAccessToken();
    }
}
