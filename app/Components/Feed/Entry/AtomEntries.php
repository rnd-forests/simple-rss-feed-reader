<?php

namespace App\Components\Feed\Entry;

class AtomEntries extends EntryCollection
{
    /**
     * {@inheritdoc}
     */
    public function getLocation()
    {
        return 'entry';
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return AtomEntry::class;
    }
}
