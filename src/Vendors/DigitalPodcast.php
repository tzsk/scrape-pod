<?php
namespace Tzsk\ScrapePod\Vendors;


use SimpleXMLElement;
use Tzsk\ScrapePod\Contracts\VendorInterface;

class DigitalPodcast extends AbstractVendor implements VendorInterface
{
	/**
	 * @var string
	 */
	const APP_ID = "podcastsearchdemo";

	/**
	 * @var string
	 */
	const SEARCH_URL = "http://api.digitalpodcast.com/v2r/search";

	/**
	 * @var string
	 */
	const FORMAT = "rss";

	/**
	 * @var int
	 */
	private $limit = 15;

	/**
	 * @var string
	 */
	private $defaultQuery = null;

	/**
	 * DigitalPodcast constructor.
	 */
	public function __construct()
	{
		$this->setDefaultQuery();
	}

	/**
	 * @return int
	 */
	public function getLimit()
	{
		return $this->limit;
	}

	/**
	 * @param int $limit
	 */
	public function setLimit($limit)
	{
		// `-1` being used here because the API returns 3 items when `results=2`.
		$this->limit = ((int) $limit - 1);
	}

	/**
	 * @return void
	 */
	public function setDefaultQuery()
	{
		$this->defaultQuery = http_build_query([
			'results' => $this->limit,
			'appid'   => self::APP_ID,
			'format'  => self::FORMAT
		]);
	}

	/**
	 * @param  string $value
	 * @return string
	 */
	public function generateUrl($value)
	{
		$value = urlencode($value);
		$url   = self::SEARCH_URL . "?keywords={$value}";

		return $url . '&' . $this->defaultQuery;
	}

	/**
	 * @param  array $response
	 * @return array
	 */
	public function build(array $response)
	{
		$xml = new SimpleXMLElement($response['search']);
		$xml = $xml->channel;

		$output['result_count'] = count($xml->item);

		foreach ($xml->item as $value) {
			$output['results'][] = [
				'title' => (string) $value->title,
				'rss'   => (string) $value->source,
				'link'  => (string) $value->link,
			];
		}

		return $output;
	}
}