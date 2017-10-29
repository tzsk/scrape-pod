<?php
namespace Tzsk\ScrapePod\Vendors;

use DateTime;
use function GuzzleHttp\Psr7\str;
use SimpleXMLElement;

abstract class AbstractVendor
{
    /**
     * @param SimpleXMLElement $feed
     *
     * @return array
     */
    public function buildFeed(SimpleXMLElement $feed)
    {
        $output = [
            'title'          => (string) $feed->channel->title,
            'description'    => (string) $feed->channel->description,
            'summary'        => (string) $this->getValueByPath($feed->channel, "summary"),
            'image'          => (string) $feed->channel->image->url,
            'site'           => (string) $feed->channel->link,
            'author'         => (string) $this->getValueByPath($feed->channel, "author"),
            'language'       => (string) $feed->channel->language,
            'categories'     => $this->fetchCategories($feed->channel),
            'episode_count'  => (int) count($feed->channel->item),
            'episodes'       => $this->getEpisodes($feed->channel)
        ];

        return $output;
    }

    /**
     * @param SimpleXMLElement|mixed $channel
     *
     * @return array
     */
    protected function getEpisodes($channel)
    {
        $items = [];
        foreach ($channel->item as $value) {
            $items[] = [
                'title'        => (string) $value->title,
                'mp3'          => $this->getAudioUrl($value),
                'size'         => $this->getEpisodeSize($value),
                'duration'     => $this->getEpisodeDuration($value),
                'description'  => (string) $value->description,
                'link'         => (string) $value->link,
                'image'        => $this->getEpisodeImage($value, $channel),
                'published_at' => $this->getPublishedDate($value),
            ];
        }

        return $items;
    }

    /**
     * @param SimpleXMLElement $value
     *
     * @return null|string
     */
    protected function getAudioUrl($value)
    {
        return isset($value->enclosure) ? (string) $value->enclosure->attributes()->url : null;
    }

    /**
     * @param SimpleXMLElement $value
     *
     * @return int
     */
    protected function getEpisodeSize($value)
    {
        return isset($value->enclosure) ? (int) $value->enclosure->attributes()->length : 0;
    }

    /**
     * @param SimpleXMLElement $item
     *
     * @return string
     */
    protected function getPublishedDate($item)
    {
        $published_at = new DateTime();
        $published_at->setTimestamp(strtotime($item->pubDate));

        return $published_at->format('Y-m-d H:i:s');
    }

    /**
     * @param SimpleXMLElement|mixed $item
     * @param string $path
     *
     * @return SimpleXMLElement
     */
    protected function getValueByPath($item, $path)
    {
        return empty($item->xpath("itunes:{$path}")) ? null :
            $item->xpath("itunes:{$path}")[0];
    }

    /**
     * @param SimpleXMLElement|mixed $item
     *
     * @return int
     */
    protected function getEpisodeDuration($item)
    {
        $duration = (string) $this->getValueByPath($item, "duration");

        $durationArray = explode(":", $duration);
        if (count($durationArray) > 1) {
            sscanf($duration, "%d:%d:%d", $hours, $minutes, $seconds);

            $duration = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
        }

        return (int) $duration;
    }

    /**
     * @param SimpleXMLElement $item
     *
     * @param SimpleXMLElement $channel
     *
     * @return string
     */
    protected function getEpisodeImage($item, $channel)
    {
        $xmlImage = $this->getValueByPath($item, "image");

        return $xmlImage ? (string) $xmlImage->attributes()->href : (string) $channel->image->url;
    }

    /**
     * @param SimpleXMLElement|mixed $channel
     *
     * @return array
     */
    protected function fetchCategories($channel)
    {
        $categories = $channel->xpath('itunes:category');

        $items = [];
        foreach ($categories as $category) {
            $item = ['title' => (string) $category->attributes()->text, 'children' => []];

            if (! empty($category->xpath('itunes:category'))) {
                $inner = $this->fetchCategories($category);

                $item['children'] = $inner;
            }

            $items[] = $item;
        }

        return $items;
    }
}
