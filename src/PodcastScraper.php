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
            return $this->failedResponse("Search keyword cannot be empty");
        }

        try {
            $response = $this->search(new Request, $value);
            return [
                'status'      => true,
                'data'        => $this->vendor->build($response)
            ];
        } catch (Exception $except) {
            return $this->failedResponse($except->getMessage());
        }
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
            return $this->failedResponse("Feed Url cannot be empty");
        }

        try {
            return [
                'status'      => true,
                'data'        => $this->vendor->buildFeed($this->getFeedFromUrl($feedUrl))
            ];
        } catch (Exception $except) {
            return $this->failedResponse($except->getMessage());
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
    * @return PodcastScraper
    */
    public function original()
    {
        $this->vendor->original();

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

    /**
     * @param string $feedUrl
     *
     * @return mixed
     */
    protected function getFeedFromUrl($feedUrl)
    {
        $response = $this->read(new Request, $feedUrl);

        libxml_use_internal_errors(true);

        try {
            $feed = new SimpleXMLElement($response['feed'], LIBXML_NOCDATA, false);
        } catch (Exception $except) {
            $feed = new SimpleXMLElement(Xml::repair($response['feed']), LIBXML_NOCDATA, false);
        }

        return $feed;
    }

    /**
     * @param string $message
     *
     * @return array
     */
    protected function failedResponse($message)
    {
        return [
            'status'      => false,
            'message'     => $message
        ];
    }
}
