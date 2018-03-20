<?php

namespace App\Components\Feed\Entry;

interface Entry
{
    /**
     * Gets the identifier of the entry.
     *
     * @return string
     */
    public function getId();

    /**
     * Gets the title of the entry.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Gets the published date of the entry.
     *
     * @return \DateTime
     */
    public function getPublishedDate();

    /**
     * Gets the updated date of the entry.
     *
     * @return \DateTime
     */
    public function getModifiedDate();

    /**
     * Gets a specific author of the entry, default to the first author.
     *
     * @return string|null
     */
    public function getAuthor($position = 0);

    /**
     * Gets the list of all authors associated with the entry.
     *
     * @return array
     */
    public function getAuthors();

    /**
     * Gets the content of the entry.
     *
     * @return string
     */
    public function getContent();

    /**
     * Gets the link of the entry.
     *
     * @return string
     */
    public function getLink();

    /**
     * Gets the description of the entry.
     *
     * @return string
     */
    public function getDescription();
}
