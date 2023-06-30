# WP Media Crawler - Explanation

## The crawler engine

After searching for the best solutions for crawling links using PHP/WordPress, I chose to use the [Symfony Crawler](https://github.com/symfony/dom-crawler). This libs is by far the most popular one and it is very straightforward. I also could use the native PHP DomDocument class to crawl, but I have some experience with this guy and if I can avoid handling its limitations and weirdness, I go for it. 

## Package template changes

I needed to adjust some settings of your package template. I mainly changed the composer.json, because some of the libs weren't being found. I did some general changes as well, so I would be able to feel a little bit more comfortable. The changes were the first thing I did and you can follow the main ones here in this commit: https://github.com/Hercilio1/wp-media-crawler/commit/998289dd

I also adjusted some PHP capabilities configurations to be easier to work with PHP 7.4 within PHPCS.
