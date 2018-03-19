<?php

namespace App\Components\Feed;

use Carbon\Carbon;

class AtomFeed extends BaseFeed implements Feed
{
    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return (string) $this->feedXml->title;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublishedDate()
    {
        return Carbon::parse($this->feedXml->updated)->toDateTimeString();
    }
}
