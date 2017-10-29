# Scrape Pod

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]


Scrape Pod is a Podcast Searching and XML parsing tool. You can search podcasts from `Itunes` or `DigitalPodcast`. This tool is designed for Laravel 5.1 and above but this can be used outside laravel as well.


## Install

Via Composer

``` bash
$ composer require tzsk/scrape-pod
```

## Configure

If you are using Laravel 5.4 or Below you need to perform following steps to configure.

And if you are using this package outside Laravel then you don't have to perform these steps.


In your `config/app.php` file place the Provider and alias like so.

```php
'providers' => [
    ...
    Tzsk\ScrapePod\Provider\ScrapePodServiceProvider::class,
    ...
],

'aliases' => [
    ...
    'ScrapePod' => Tzsk\ScrapePod\Facade\ScrapePod::class,
    ...
],
```

## Usage with Laravel

**Searching Example:**

At the top of any file use the `namespace`;

``` php
...
use Tzsk\ScrapePod\Facade\ScrapePod;
...
```

Now, inside any method use it like this:

```php
$response = ScrapePod::search("Laravel");

# OR

$response = ScrapePod::limit(15)->search("Laravel");

# OR use Digital Podcast to Search.

$response = ScrapePod::digitalPodcast()->search("Laravel");
$response = ScrapePod::digitalPodcast()->limit(15)->search("Laravel");

```

**XML Parsing Example:**

From the search results you can find the `rss` feed url. You can use that URL or any other Feed URL you want.

```php
$data = ScrapePod::feed($feedURL);
```

This will give you the Sraped Result Set of any information found on that Feed URL.


## Usage outside Laravel

At the top of any file use the `namespace`;

``` php
...
use Tzsk\ScrapePod\ScrapePodcast;
...
```

Now, inside any method use it like this:

```php
$scraper = new ScrapePodcast();
$response = $scraper->search("Laravel");

# OR

$response = $scraper->limit(15)->search("Laravel");

# OR use Digital Podcast to Search.

$response = $scraper->digitalPodcast()->search("Laravel");
$response = $scraper->digitalPodcast()->limit(15)->search("Laravel");

```

**XML Parsing Example:**

From the search results you can find the `rss` feed url. You can use that URL or any other Feed URL you want.

```php
$data = $scraper->feed($feedURL);
```

This will give you the Sraped Result Set of any information found on that Feed URL.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email mailtokmahmed@gmail.com instead of using the issue tracker.

## Credits

- [Kazi Mainuddin Ahmed][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/tzsk/scrape-pod.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/tzsk/scrape-pod/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/tzsk/scrape-pod.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/tzsk/scrape-pod.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/tzsk/scrape-pod.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/tzsk/scrape-pod
[link-travis]: https://travis-ci.org/tzsk/scrape-pod
[link-scrutinizer]: https://scrutinizer-ci.com/g/tzsk/scrape-pod/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/tzsk/scrape-pod
[link-downloads]: https://packagist.org/packages/tzsk/scrape-pod
[link-author]: https://github.com/tzsk
[link-contributors]: ../../contributors
