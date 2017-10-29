<?php
namespace Tzsk\ScrapePod;

use Tzsk\ScrapePod\Contracts\VendorInterface;
use Tzsk\ScrapePod\Vendors\DigitalPodcast;
use Tzsk\ScrapePod\Vendors\Itunes;

class ScrapePodcast
{
    /**
     * @var VendorInterface
     */
    protected $vendor;

    /**
     * @var int
     */
    protected $count = 15;

    /**
     * ScrapePodcast constructor.
     */
    public function __construct()
    {
        $this->vendor = new Itunes();
    }

    /**
     * @return ScrapePodcast
     */
    public function itunes()
    {
        $this->vendor = new Itunes();

        return $this;
    }

    /**
     * @return ScrapePodcast
     */
    public function digitalPodcast()
    {
        $this->vendor = new DigitalPodcast();

        return $this;
    }

    /**
     * @param int $count
     *
     * @return ScrapePodcast
     */
    public function limit($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @param string $term
     *
     * @return array
     */
    public function search($term)
    {
        return $this->engine()->get($term);
    }

    /**
     * @param string $feed
     *
     * @return array
     */
    public function feed($feed)
    {
        return $this->engine()->find($feed);
    }

    /**
     * @return PodcastScraper
     */
    protected function engine()
    {
        return (new PodcastScraper($this->vendor))->limit($this->count);
    }
}
