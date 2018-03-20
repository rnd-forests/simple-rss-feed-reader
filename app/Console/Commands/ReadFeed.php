<?php

namespace App\Console\Commands;

use App\Feed;
use App\Components\Feed\Reader;
use Illuminate\Console\Command;
use App\Exceptions\InvalidFeedException;

class ReadFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:read {feed : The ID or URL of the feed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display the content of a feed using ID or URL';

    /**
     * @var \App\Components\Feed\Reader
     */
    protected $reader;

    /**
     * @param \App\Components\Feed\Reader $reader
     */
    public function __construct(Reader $reader)
    {
        parent::__construct();

        $this->reader = $reader;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $feed = $this->argument('feed');

        try {
            if (filter_var($feed, FILTER_VALIDATE_URL)) {
                $data = $this->reader->readFromUrl($feed);
            } else {
                $feed = (int) $feed;
                $instance = Feed::find($feed);

                if (is_null($instance)) {
                    $this->error('Cannot find the given feed.');
                    return;
                }

                $data = $this->reader->readFeed($instance);
            }
        } catch (InvalidFeedException $e) {
            $this->error('Cannot read the content of the given feed.');
            return;
        }

        $this->showTable($data);
    }

    /**
     * Converts feeds to table format.
     *
     * @param  \Illuminate\Support\Collection $data
     * @return void
     */
    protected function showTable($data)
    {
        $headers = ['Title', 'Published Date'];

        $rows = $data->get('entries')->map(function ($entry) {
            return [$entry->getTitle(), $entry->getPublishedDate()];
        });

        $this->table($headers, $rows);
    }
}
