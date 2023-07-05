<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

namespace WP_Media\Crawler\Custom\Crawlers;

/**
 * Interface ICrawler. Interface of any crawler.
 */
interface ICrawler {

	/**
	 * Abstract method 'crawl'. All crawlers should crawl.
	 *
	 * @return array The crawled data.
	 */
	public function crawl() : array;
}
