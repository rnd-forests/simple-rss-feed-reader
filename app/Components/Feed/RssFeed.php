<?php

namespace App\Components\Feed;

use Carbon\Carbon;

class RssFeed extends BaseFeed implements Feed
{
    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return (string) $this->feedXml->channel->title;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublishedDate()
    {
        $date = optional($this->feedXml->channel)->pubDate;

        if (is_null($date)) {
            return null;
        }

        return Carbon::parse($date)->toDateTimeString();
    }
}
