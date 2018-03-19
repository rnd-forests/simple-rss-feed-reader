<?php

namespace App\Components\Feed;

interface Feed
{
    /**
     * Gets the feed title.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Gets the published date of the feed.
     *
     * @return string
     */
    public function getPublishedDate();

    /**
     * Gets all feed entries.
     *
     * @return \Illuminate\Support\Collection
     */
    public function entries();

    /**
     * Converts the feed to a collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function toCollection();
}
