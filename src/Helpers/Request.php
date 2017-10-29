<?php
namespace Tzsk\ScrapePod\Helpers;


class Request
{
	/**
	 * @var string
	 */
	private $contentType;

	/**
	 * @var int
	 */
	private $statusCode;

	/**
	 * @param string $url
	 * @param array $options
	 *
	 * @return string
	 */
	public function create($url, array $options = [])
	{
		$request = curl_init($url);

		$default_options = [
           CURLOPT_FAILONERROR    => true,
           CURLOPT_FOLLOWLOCATION => true,
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_TIMEOUT        => 15,
           CURLOPT_SSL_VERIFYPEER => 0,
           CURLINFO_HEADER_OUT    => true
       ] + $options;

		curl_setopt_array($request, $default_options);

		$result = curl_exec($request);
		$this->statusCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
		$this->contentType = curl_getinfo($request, CURLINFO_CONTENT_TYPE);
		curl_close($request);

		return $result;
	}

	/**
	 * @return int
	 */
	public function getStatusCode()
	{
		return $this->statusCode;
	}

	/**
	 * @return string
	 */
	public function getContentType()
	{
		return $this->contentType;
	}
}