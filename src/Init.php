<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

namespace WP_Media\Crawler;

use WP_Media\Crawler\Custom\Crawlers\LinksCrawler;
use WP_Media\Crawler\Custom\Sitemap\SitemapBuilder;
use WP_Media\Crawler\Custom\Sitemap\SitemapFile;
use WP_Media\Crawler\Custom\Sitemap\SitemapRouter;
use WP_Media\Crawler\Custom\Sitemap\SitemapWriter;

/**
 * Class Init. Initializes the plugin and the sequential main flow.
 */
class Init {

	/**
	 * Init method.
	 */
	public static function init() {
		SitemapRouter::init();
		if ( isset( $_GET['wp_media'] ) && 'test' === $_GET['wp_media'] ) {
			$links        = ( new LinksCrawler( home_url() ) )->crawl();
			$sitemap_html = ( new SitemapBuilder( $links ) )->build();
			SitemapFile::save( $sitemap_html );
			echo '<pre>';
			print_r( $links );
			exit;
		}
	}
}
