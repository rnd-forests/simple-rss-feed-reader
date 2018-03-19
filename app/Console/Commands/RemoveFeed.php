<?php

namespace App\Console\Commands;

use App\Feed;
use Illuminate\Console\Command;

class RemoveFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:remove {feeds* : ID of the feeds to be removed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove feeds by their ID';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $feedIds = $this->argument('feeds');

        foreach ($feedIds as $id) {
            $id = (int) $id;
            $feed = Feed::find($id);

            if (is_null($feed)) {
                $this->error("Cannot find the feed with ID of '{$id}'");
                continue;
            }

            $feed->delete();
            $this->info("Deleted feed with ID: '{$id}'");
        }
    }
}
