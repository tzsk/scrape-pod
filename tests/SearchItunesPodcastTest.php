<?php
namespace Tzsk\ScrapePod;

class SearchItunesPodcastTest extends TestCase
{
    use SearchResultTrait;

    protected $result;
    protected $itunesResult;
    protected $failedResult;
    protected $itunesOriginal;

    protected function setUp()
    {
        parent::setUp();
        $this->result = $this->scraper->search("laravel");
        $this->itunesResult = $this->scraper->itunes()->search("laravel");
        $this->itunesOriginal = $this->scraper->original()->search("laravel");
        $this->failedResult = $this->scraper->search("");
    }

    public function testDefaultVendorIsItunes()
    {
        $this->assertSame($this->result, $this->itunesResult);
    }

    public function testOriginalItunesResponse()
    {
        $this->assertCount(2, $this->itunesOriginal);
        $data = $this->itunesOriginal['data'];
        $this->assertCount($data->resultCount, $data->results);
    }
}
