<?php
namespace Tzsk\ScrapePod\Contracts;

use SimpleXMLElement;

interface VendorInterface
{
	/**
	 * @param string $value
	 *
	 * @return string
	 */
	public function generateUrl($value);

	/**
	 * @param array $response
	 *
	 * @return array
	 */
	public function build(array $response);

	/**
	 * @return void
	 */
	public function setDefaultQuery();

	/**
	 * @return int
	 */
	public function getLimit();

	/**
	 * @param int $limit
	 *
	 * @return void
	 */
	public function setLimit($limit);

	/**
	 * @param SimpleXMLElement $feed
	 *
	 * @return array
	 */
	public function buildFeed(SimpleXMLElement $feed);
}