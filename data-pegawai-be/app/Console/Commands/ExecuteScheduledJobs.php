<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobExecution;
use App\Jobs\DBMigration;

class ExecuteScheduledJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:executescheduled';

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
            $je = JobExecution::where('status', 'scheduled')->where('start_time','<=',date('Y-m-d H:i:s'))->first();
            if (!$je) return 0;
            $je->setRunning();
            $j = new DBMigration($je);
            $j->run();
        }
        return 0;
    }
}
