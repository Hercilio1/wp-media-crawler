<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

namespace WP_Media\Crawler;

use WP_Media\Crawler\Custom\Crawlers\LinksCrawler;

/**
 * Class Init. Initializes the plugin and the sequential main flow.
 */
class Init {

	/**
	 * Init method.
	 */
	public static function init() {
		if ( isset( $_GET['wp_media'] ) && 'test' === $_GET['wp_media'] ) {
			$links = ( new LinksCrawler( home_url() ) )->crawl();
			echo '<pre>';
			print_r( $links );
			exit;
		}
	}
}
