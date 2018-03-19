<?php

namespace App\Components\Feed;

use App\Exceptions\InvalidFeedException;
use App\Feed;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Zttp\PendingZttpRequest as Zttp;

class Reader
{
    const RSS_TYPE = 'rss';
    const ATOM_TYPE = 'atom';

    const RSS_MIME_TYPES = [
        'text/xml',
        'application/xml',
        'application/rss+xml',
    ];

    const ATOM_MIME_TYPES = [
        'application/atom+xml',
    ];

    /**
     * @var array
     */
    protected $feedMap = [
        self::RSS_TYPE => RssFeed::class,
        self::ATOM_TYPE => AtomFeed::class,
    ];

    /**
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @param \Illuminate\Contracts\Config\Repository $config
     */
    public function __construct(Cache $cache, Config $config)
    {
        $this->cache = $cache;
        $this->config = $config;
    }

    /**
     * Reads and parses the feed content from a URL.
     *
     * @param  string $url
     * @return \Illuminate\Support\Collection
     */
    public function readFromUrl($url)
    {
        $response = $this->get($url);

        if ($response instanceof Response) {
            throw new InvalidFeedException;
        }

        $this->type = $this->detectType($response);
        $feedXml = convert_to_xml($response->body());

        $class = $this->feedMap[$this->type];
        $feed = new $class($feedXml, $this->type);

        return $feed->toCollection();
    }

    /**
     * Reads and parses the content of the feed.
     *
     * @param  \App\Feed $feed
     * @return \Illuminate\Support\Collection
     */
    public function freshReadFeed(Feed $feed)
    {
        return $this->readFromUrl($feed->url);
    }

    /**
     * Reads and parses the content of the feed.
     *
     * @param  \App\Feed $feed
     * @return \Illuminate\Support\Collection
     */
    public function readFeed(Feed $feed)
    {
        $duration = $this->config->get('rss.cache_duration');

        return $this->cache
            ->tags($this->config->get('rss.cache_tag_key'))
            ->remember($feed->getCacheKey(), $duration, function () use ($feed) {
                return $this->freshReadFeed($feed);
            });
    }

    /**
     * Detects RSS feed type using header information.
     *
     * @param  \Zttp\ZttpResponse $response
     * @return string
     */
    protected function detectType($response)
    {
        $contentType = array_get($response->headers(), 'Content-Type');

        if ($this->isAtomFeed($contentType)) {
            return self::ATOM_TYPE;
        }

        if ($this->isRssFeed($contentType)) {
            return self::RSS_TYPE;
        }

        throw new InvalidFeedException;
    }

    /**
     * Checks for Atom feed based on the content type.
     *
     * @param  string  $contentType
     * @return boolean
     */
    protected function isAtomFeed($contentType)
    {
        foreach (self::ATOM_MIME_TYPES as $mime) {
            if (Str::startsWith($contentType, $mime)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks for RSS feed based on the content type.
     *
     * @param  string  $contentType
     * @return boolean
     */
    protected function isRssFeed($contentType)
    {
        foreach (self::RSS_MIME_TYPES as $mime) {
            if (Str::startsWith($contentType, $mime)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Performs a GET request.
     *
     * @codeCoverageIgnore
     * @param string $url
     * @param array $params
     * @return \Illuminate\Http\JsonResponse|array
     */
    public function get($url, $params = [])
    {
        return $this->request('GET', $url, $params);
    }

    /**
     * Sends external request.
     *
     * @param string $method
     * @param string $url
     * @param array $params
     * @param array $options
     * @return \Illuminate\Http\JsonResponse|array
     * @throws \GuzzleHttp\Exception\RequestException
     */
    protected function request($method, $url, $params = [], $options = [])
    {
        try {
            $method = trim(strtolower($method));
            return $this->formatResponse(
                $this->client($options)->$method($url, $params)
            );
        } catch (RequestException $e) {
            Log::error($e->getMessage());
            return response('', 400);
        }
    }

    /**
     * Converts response to proper format.
     *
     * @param  \Zttp\ZttpResponse $response
     * @return \Illuminate\Http\JsonResponse|array
     */
    protected function formatResponse($response)
    {
        if ($response->isOk()) {
            return $response;
        }

        return response('', 400);
    }

    /**
     * Configures Guzzle request.
     *
     * @param  array  $options
     * @return \Zttp\PendingZttpRequest
     */
    protected function client($options = [])
    {
        $zttp = resolve(Zttp::class);
        $zttp->options = array_merge($zttp->options, [
            'timeout' => 10.0,
            'connect_timeout' => 10.0,
        ]);

        foreach ($options as $option) {
            if (method_exists($zttp, $option)) {
                $zttp->$option();
            }
        }

        return $zttp;
    }
}
