<?php
namespace Tzsk\ScrapePod;

use Exception;
use SimpleXMLElement;
use Tzsk\ScrapePod\Contracts\VendorInterface;
use Tzsk\ScrapePod\Helpers\Request;
use Tzsk\ScrapePod\Helpers\Xml;

class PodcastScraper
{
	/**
	 * @var VendorInterface
	 */
	private $vendor;

	/**
	 * @param VendorInterface $vendor
	 */
	public function __construct(VendorInterface $vendor)
	{
		$this->vendor = $vendor;
	}

	/**
	 * @param  string $value
	 * @return array
	 */
	public function get($value)
	{
		if (strlen($value) < 1) {
			return [
				'status'      => false,
				'message'     => "Search keyword cannot be empty"
			];
		}

		try {
			$response = $this->search(new Request, $value);
			$output   = [
				'status'      => true,
				'data'        => $this->vendor->build($response)
			];
		} catch (Exception $except) {
			$output = [
				'status'      => false,
				'message'     => $except->getMessage()
			];
		}

		return $output;
	}

	/**
	 * @param  Request $request
	 * @param  string  $value
	 * @return array
	 * @throws Exception
	 */
	private function search(Request $request, $value)
	{
		$response = $request->create($this->vendor->generateUrl($value));

		if (is_null($response)) {
			throw new Exception("Request to Itunes API failed", $request->getStatusCode());
		}

		return [
			'search'      => $response,
			'status'      => true,
		];
	}

	/**
	 * @param  string $feedUrl
	 * @return array
	 */
	public function find($feedUrl)
	{
		if (strlen($feedUrl) < 1) {
			return [
				'status'      => false,
				'message'     => "Feed Url cannot be empty"
			];
		}

		try {
			$response = $this->read(new Request, $feedUrl);

			libxml_use_internal_errors(true);

			try {
				$feed = new SimpleXMLElement($response['feed'], LIBXML_NOCDATA, false);
			} catch (Exception $except) {
				$response_repaired = Xml::repair($response['feed']);
				$feed              = new SimpleXMLElement($response_repaired, LIBXML_NOCDATA, false);
			}

			return [
				'status'      => true,
				'data'        => $this->vendor->buildFeed($feed)
			];
		} catch (Exception $except) {
			return [
				'status'      => true,
				'message'     => $except->getMessage()
			];
		}
	}

	/**
	 * @param  int $limit
	 * @return PodcastScraper
	 */
	public function limit($limit)
	{
		$this->vendor->setLimit($limit);
		$this->vendor->setDefaultQuery();

		return $this;
	}

	/**
	 * @param  Request $request
	 * @param  string  $feedUrl
	 * @return array
	 * @throws Exception
	 */
	private function read(Request $request, $feedUrl)
	{
		$output = $request->create($feedUrl);

		if (is_null($output)) {
			throw new Exception("Request to RSS failed", $request->getStatusCode());
		}

		return [
			'feed'        => $output,
			'status'      => true,
		];
	}
}