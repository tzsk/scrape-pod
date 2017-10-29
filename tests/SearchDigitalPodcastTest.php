<?php
namespace Tzsk\ScrapePod;

class SearchDigitalPodcastTest extends TestCase
{
    use SearchResultTrait;

    protected $result;
    protected $failedResult;

    protected function setUp()
    {
        parent::setUp();
        $this->result = $this->scraper->digitalPodcast()->search("laravel");
        $this->failedResult = $this->scraper->search("");
    }
}
