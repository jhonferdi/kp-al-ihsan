<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobExecution;
use App\Jobs\DBMigration;

class ExecutePendingJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:executepending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        while (true) {
            $je = JobExecution::where('status', 'new')->first();
            if (!$je) {
                sleep(10);
            } else {
                $je->setRunning();
                $j = new DBMigration($je);
                $j->run();
            }
        }
        return 0;
    }
}
