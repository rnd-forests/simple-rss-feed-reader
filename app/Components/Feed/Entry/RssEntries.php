<?php

namespace App\Components\Feed\Entry;

class RssEntries extends EntryCollection
{
    /**
     * {@inheritdoc}
     */
    public function getLocation()
    {
        return 'channel.item';
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return RssEntry::class;
    }
}
