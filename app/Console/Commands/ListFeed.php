<?php

namespace App\Console\Commands;

use App\Feed;
use App\User;
use Illuminate\Console\Command;

class ListFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:list
        {user? : The ID of a given user}
        {--limit=10 : Limit the number of feeds returned}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List feeds of a user or all feeds';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userId = $this->argument('user');
        $limit = $this->option('limit');

        if (is_null($userId)) {
            $query = Feed::query();
        } else {
            $user = User::findOrFail($userId);
            $query = Feed::where('user_id', $user->id);
        }

        $feeds = $query->latest()->limit($limit)->get();

        $this->toTable($feeds);
    }

    /**
     * Converts feeds to table format.
     *
     * @param  \Illuminate\Database\Eloquent\Collection $feeds
     * @return void
     */
    protected function toTable($feeds)
    {
        $headers = ['Title', 'URL'];

        $rows = $feeds->map(function ($feed) {
            return [$feed->title, $feed->url];
        });

        $this->table($headers, $rows);
    }
}
