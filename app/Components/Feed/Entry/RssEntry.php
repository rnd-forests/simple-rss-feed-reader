<?php

namespace App\Components\Feed\Entry;

use Carbon\Carbon;

class RssEntry extends BaseEntry implements Entry
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return optional($this->entryXml)->guid;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublishedDate()
    {
        $date = optional($this->entryXml)->pubDate;

        if (is_null($date)) {
            return null;
        }

        return Carbon::parse($date)->toDateTimeString();
    }

    /**
     * {@inheritdoc}
     */
    public function getModifiedDate()
    {
        return $this->getPublishedDate();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthors()
    {
        return (array) optional($this->entryXml)->author;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return $this->getDescription();
    }

    /**
     * {@inheritdoc}
     */
    public function getLink()
    {
        return (string) optional($this->entryXml)->link;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return purify((string) optional($this->entryXml)->description);
    }
}
