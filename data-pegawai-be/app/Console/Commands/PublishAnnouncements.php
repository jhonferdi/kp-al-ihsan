<?php

namespace App\Console\Commands;

use App\Models\Announcement;
use Illuminate\Console\Command;

class PublishAnnouncements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:publish-announcements';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish announcements';

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
        $announcementList = Announcement::query()
            ->where('status', 'draft')
            ->where('date_time', '<=', date('Y-m-d H:i:s'))
            ->get();
            
        if($announcementList->count() > 0){
            foreach($announcementList as $announcement){
                Announcement::where('id', $announcement->id)
                    ->update([
                        'status' => 'publish'
                    ]);
            }
        }

        return 0;
    }
}
