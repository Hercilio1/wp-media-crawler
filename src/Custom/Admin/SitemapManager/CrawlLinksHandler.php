<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

namespace WP_Media\Crawler\Custom\Admin\SitemapManager;

use Exception;
use WP_Media\Crawler\Custom\Crawlers\LinksCrawler;
use WP_Media\Crawler\Custom\Sitemap\SitemapBuilder;
use WP_Media\Crawler\Custom\Sitemap\SitemapFile;
use WP_Media\Crawler\Custom\Sitemap\SitemapLinksStorage;

/**
 * Class CrawlLinksHandler. Responsible for handling the crawl sitemap links request.
 */
class CrawlLinksHandler {

	/**
	 * Register post hook
	 */
	public static function init() {
		add_action( 'admin_post_wp_media_crawler_crawl_sitemap_links', [ __CLASS__, 'handle_crawl_sitemap_links' ] );
	}

	/**
	 * Handle the crawl sitemap links request.
	 */
	public static function handle_crawl_sitemap_links() {
		check_ajax_referer( 'wp_media_crawler_crawl_sitemap_links' );

		if ( ! current_user_can( 'administrator' ) ) {
			wp_safe_redirect( wp_get_referer() );
			die();
		}

		try {
			$links = ( new LinksCrawler( home_url() ) )->crawl();
			SitemapLinksStorage::store( $links );
		} catch ( Exception $e ) {
			wp_die( esc_html( $e->getMessage() ) );
			// TODO: Improve error handling.
		}

		wp_safe_redirect( wp_get_referer() );
		die();
	}
}
