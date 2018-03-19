<?php

namespace App\Components\Feed\Entry;

use SimpleXMLElement;
use InvalidArgumentException;

abstract class BaseEntry
{
    /**
     * The entry in XML object format.
     *
     * @var \SimpleXMLElement
     */
    protected $entryXml;

    protected $entryXmlString;

    /**
     * @param \SimpleXMLElement $entryXml
     */
    public function __construct(SimpleXMLElement $entryXml)
    {
        $this->entryXml = $entryXml;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return (string) $this->entryXml->title;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthor($position = 0)
    {
        $authors = $this->getAuthors();

        if (is_null($authors)) {
            return null;
        }

        if (array_key_exists($position, $authors)) {
            return array_get($authors, $position);
        }

        throw new InvalidArgumentException('Invalid author position provided.');
    }

    /**
     * Converts SimpleXMLElement property to XML string for serialization.
     *
     * @return array
     */
    public function __sleep()
    {
        $this->entryXmlString = $this->entryXml->asXML();

        return ['entryXmlString'];
    }

    /**
     * Converts XML string to SimpleXMLElement object when unserializing.
     *
     * @return void
     */
    public function __wakeup()
    {
        $this->entryXml = convert_to_xml($this->entryXmlString);
    }
}
