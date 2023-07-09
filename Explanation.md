# WP Media Crawler - Explanation

## The crawler engine

After searching for the best solutions for crawling links using PHP/WordPress, I chose to use the [Symfony Crawler](https://github.com/symfony/dom-crawler). This lib is by far the most popular and very straightforward. I also could use the native PHP DomDocument class to crawl, but I have some experience with this guy and if I can avoid handling its limitations and weirdness, I go for it.

## Where to save the sitemap.html

First I thought about saving the sitemap.html file in the WP root through the ABSPATH constant or saving it in the wp-content/uploads folders. But I felt like there were better approaches than these. So I decided to look up some solutions made by some big guys. Using Yoast as a reference, I decided to build the sitemap.html in runtime and to serve it through PHP using rewrite rules (as Yoast does with the sitemap.xml). This solution is more secure, easier to test, and much more straightforward.

## Package template changes

I needed to adjust some settings of your package template. I mainly changed the composer.json, because some of the libs weren't being found. I did some general changes as well, so I would be able to feel a little bit more comfortable. The changes were the first thing I did and you can follow the main ones here in this commit: https://github.com/Hercilio1/wp-media-crawler/commit/998289dd

I also adjusted some PHP capabilities configurations to be easier to work with PHP 7.4 within PHPCS.

## Travis CI

The plugin runs in PHP 7.2, 7.3, and 7.4 without any errors, but the Unit tests don't. The problem isn't in my code, though. It is in the Yoast TestCase class (the wp media test library uses it). You can check out the error here: https://app.travis-ci.com/github/Hercilio1/wp-media-crawler/jobs/605690965.
So in order to pass the Travis builds, I disabled the PHP 7.2 verification.
