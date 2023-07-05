<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

namespace WP_Media\Crawler\Custom\Tasks;

use Exception;
use WP_Media\Crawler\Custom\Crawlers\LinksCrawler;
use WP_Media\Crawler\Custom\Sitemap\SitemapLinksStorage;

/**
 * Class CrawlLinksTask. Responsible for crawling the links and storing them.
 */
class CrawlLinksTask extends AbstractTask {

	/**
	 * The task runner.
	 */
	public function run() : void {
		try {
			$links = ( new LinksCrawler( home_url() ) )->crawl();
			SitemapLinksStorage::store( $links );
		} catch ( Exception $e ) {
			// TODO: Add a proper error logging.
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			error_log( 'The following ocurred while crawling the links: ' . $e->getMessage() );
		}
	}
}
