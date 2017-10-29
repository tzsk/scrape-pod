<?php
namespace Tzsk\ScrapePod\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class ScrapePod
 * @see \Tzsk\ScrapePod\ScrapePodcast
 */
class ScrapePod extends Facade
{

    /**
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'tzsk-scrape-pod';
    }
}
