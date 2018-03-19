<?php

namespace App\Components\Feed\Entry;

use SimpleXMLElement;
use RuntimeException;

abstract class EntryCollection
{
    /**
     * The XML representation of the feed.
     *
     * @var \SimpleXMLElement
     */
    protected $feedXml;

    /**
     * @param SimpleXMLElement $feedXml
     */
    public function __construct(SimpleXMLElement $feedXml)
    {
        $this->feedXml = $feedXml;
    }

    /**
     * Gets all entries inside the feed.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get()
    {
        $feed = $this->feedXml;
        $keys = explode('.', $this->getLocation());

        if (empty($keys)) {
            throw new RuntimeException('Cannot get the entries location.');
        }

        foreach ($keys as $key) {
            $feed = $feed->{$key};
        }

        $entries = [];
        $class = $this->getIdentifier();
        foreach ($feed as $entry) {
            $entries[] = new $class($entry);
        }

        return collect($entries);
    }

    /**
     * Gets the location where entries are located.
     * If nested position, separating by dot.
     *
     * @return string
     */
    abstract public function getLocation();

    /**
     * Gets the base entry class.
     *
     * @return string
     */
    abstract public function getIdentifier();
}
