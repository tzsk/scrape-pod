<?php
namespace Tzsk\ScrapePod;

trait SearchResultTrait
{
    public function testFailedOnEmptySearch()
    {
        $this->assertInternalType('array', $this->failedResult);
        $this->assertFalse($this->failedResult['status']);
        $this->assertArrayHasKey('message', $this->failedResult);
    }

    public function testSearchResponse()
    {
        $this->assertInternalType('array', $this->result);
        $this->assertCount(2, $this->result);
        $this->assertTrue($this->result['status']);
        $this->assertArrayHasKey('data', $this->result);
    }

    public function testSearchData()
    {
        $data = $this->result['data'];

        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('result_count', $data);
        $this->assertArrayHasKey('results', $data);

        $this->assertEquals($data['result_count'], count($data['results']));
    }

    public function testSearchResultsHasFeedUrl()
    {
        $results = $this->result['data']['results'];

        $this->assertInternalType('array', $results);
        $this->assertArrayHasKey('rss', $results[0]);
    }
}
