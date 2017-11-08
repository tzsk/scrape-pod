<?php
namespace Tzsk\ScrapePod\Vendors;

use Tzsk\ScrapePod\Contracts\VendorInterface;

class Itunes extends AbstractVendor implements VendorInterface
{
    /**
     * @var string
     */
    const SEARCH_URL = "https://itunes.apple.com/search";

    /**
     * @var string
     */
    const LOOKUP_URL = "https://itunes.apple.com/lookup";

    /**
     * @var string
     */
    const ENTITY = "podcast";

    /**
     * @var string
     */
    const MEDIA = "podcast";

    /**
     * @var int
     */
    private $limit = 15;

    /**
     * @var string
     */
    private $defaultQuery = null;

    /**
     * Itunes constructor.
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
        $this->limit = (int) $limit;
    }

    /**
     * @return void
     */
    public function setDefaultQuery()
    {
        $this->defaultQuery = http_build_query([
            'limit'     => $this->limit,
            'entity'    => self::ENTITY,
            'media'     => self::MEDIA
        ]);
    }

    /**
     * @param  string $value
     * @return string
     */
    public function generateUrl($value)
    {
        $value = is_string($value) ? urlencode($value) : $value;
        $url   = is_int($value) ? self::LOOKUP_URL . "?id={$value}" : self::SEARCH_URL . "?term={$value}";

        return $url . '&' . $this->defaultQuery;
    }

    /**
     * @param  array  $response
     * @return array
     */
    public function build(array $response)
    {
        $response = json_decode($response['search']);
        if ($this->isOrginal) {
            return $response;
        }
        $output['result_count'] = $response->resultCount;

        foreach ($response->results as $value) {
            $output['results'][] = [
                'itunes_id' => $value->collectionId,
                'author'    => $value->artistName,
                'title'     => $value->collectionName,
                'episodes'  => $value->trackCount,
                'image'     => $value->artworkUrl600,
                'rss'       => $value->feedUrl,
                'itunes'    => $value->collectionViewUrl,
                'genre'     => $value->primaryGenreName,
            ];
        }

        return $output;
    }

    /**
    * @return void
    */
    public function original()
    {
        $this->isOrginal = true;
    }
}
