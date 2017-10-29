<?php
namespace Tzsk\ScrapePod;

class ScrapePodcastTest extends TestCase
{
    protected $result;
    protected $failedResult;

    protected function setUp()
    {
        parent::setUp();

        $data = $this->scraper->search("laravel");

        $this->result = $this->scraper->feed($data['data']['results'][0]['rss']);
        $this->failedResult = $this->scraper->feed("");
    }

    public function testFailedOnEmptyFeedUrl()
    {
        $this->assertInternalType('array', $this->failedResult);
        $this->assertFalse($this->failedResult['status']);
        $this->assertArrayHasKey('message', $this->failedResult);
    }

    public function testScrapedArray()
    {
        $this->assertInternalType('array', $this->result);
        $this->assertTrue($this->result['status']);
        $this->assertInternalType('array', $this->result['data']);
    }

    public function testScrapedResults()
    {
        $results = $this->result['data'];

        $this->assertArrayHasKey('title', $results);
        $this->assertArrayHasKey('description', $results);
        $this->assertArrayHasKey('image', $results);
        $this->assertInternalType('array', $results['categories']);
        $this->assertArrayHasKey('title', $results['categories'][0]);
        $this->assertInternalType('array', $results['categories'][0]['children']);
        $this->assertEquals($results['episode_count'], count($results['episodes']));
    }

    public function testScarpedEpisodes()
    {
        $episode = $this->result['data']['episodes'][0];

        $this->assertArrayHasKey('title', $episode);
        $this->assertArrayHasKey('description', $episode);
        $this->assertArrayHasKey('image', $episode);
        $this->assertArrayHasKey('mp3', $episode);
        $this->assertArrayHasKey('duration', $episode);
        $this->assertArrayHasKey('size', $episode);
    }
}
