<?php
namespace Tzsk\ScrapePod\Provider;

use Illuminate\Support\ServiceProvider;
use Tzsk\ScrapePod\ScrapePodcast;

class ScrapePodServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('tzsk-scrape-pod', function () {
            return new ScrapePodcast();
        });
    }

    /**
     * @return void
     */
    public function register()
    {
        //
    }
}
