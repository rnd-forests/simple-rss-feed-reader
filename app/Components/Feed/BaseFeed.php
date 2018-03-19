<?php

namespace App\Components\Feed;

use App\Components\Feed\Entry\AtomEntries;
use App\Components\Feed\Entry\RssEntries;
use SimpleXMLElement;

class BaseFeed
{
    /**
     * Entry collection map.
     *
     * @var array
     */
    protected $entriesMap = [
        Reader::ATOM_TYPE => AtomEntries::class,
        Reader::RSS_TYPE => RssEntries::class,
    ];

    /**
     * The XML representation of the feed.
     *
     * @var \SimpleXMLElement
     */
    protected $feedXml;

    /**
     * The feed type.
     *
     * @var string
     */
    protected $type;

    /**
     * @param \SimpleXMLElement $feedXml
     */
    public function __construct(SimpleXMLElement $feedXml, $type)
    {
        $this->feedXml = $feedXml;
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function entries()
    {
        $class = $this->entriesMap[$this->type];

        return new $class($this->feedXml);
    }

    /**
     * {@inheritdoc}
     */
    public function toCollection()
    {
        return collect([
            'title' => $this->getTitle(),
            'published_date' => $this->getPublishedDate(),
            'entries' => $this->entries()->get(),
        ]);
    }
}
