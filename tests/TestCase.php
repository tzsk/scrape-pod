<?php
namespace Tzsk\ScrapePod;
use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

class TestCase extends PHPUnit_Framework_TestCase
{
	/**
	 * @var ScrapePodcast
	 */
	public $scraper;

	protected function setUp()
	{
		parent::setUp();

		$this->scraper = new ScrapePodcast();
	}
}