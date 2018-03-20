<?php

namespace App\Components\Feed\Entry;

use Carbon\Carbon;

class AtomEntry extends BaseEntry implements Entry
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return optional($this->entryXml)->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublishedDate()
    {
        return $this->getModifiedDate();
    }

    /**
     * {@inheritdoc}
     */
    public function getModifiedDate()
    {
        return Carbon::parse($this->entryXml->updated)->toDateTimeString();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthors()
    {
        return (array) optional($this->entryXml)->author->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return purify(optional($this->entryXml)->content);
    }

    /**
     * {@inheritdoc}
     */
    public function getLink()
    {
        return (string) optional($this->entryXml)->link['href'];
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return purify((string) optional($this->entryXml)->summary);
    }
}
