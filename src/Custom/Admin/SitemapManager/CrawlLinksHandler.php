<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

namespace WP_Media\Crawler\Custom\Admin\SitemapManager;

use Exception;
use WP_Media\Crawler\Custom\Crawlers\Exceptions\WebpageException;
use WP_Media\Crawler\Custom\Crawlers\LinksCrawler;
use WP_Media\Crawler\Custom\Sitemap\SitemapLinksStorage;

/**
 * Class CrawlLinksHandler. Responsible for handling the crawl sitemap links request.
 */
class CrawlLinksHandler {

	/**
	 * Register post hook
	 */
	public static function init() : void {
		add_action( 'admin_post_wp_media_crawler_crawl_sitemap_links', [ __CLASS__, 'handle_crawl_sitemap_links' ] );
	}

	/**
	 * Handle the crawl sitemap links request.
	 */
	public static function handle_crawl_sitemap_links() : void {
		check_ajax_referer( 'wp_media_crawler_crawl_sitemap_links' );

		if ( ! current_user_can( 'administrator' ) ) {
			wp_safe_redirect( wp_get_referer() );
			die();
		}

		try {
			$links = ( new LinksCrawler( home_url() ) )->crawl();
			SitemapLinksStorage::store( $links );
		} catch ( WebpageException $e ) {
			self::handle_error( $e->get_error_message_html() );
		} catch ( Exception $e ) {
			self::handle_error( 'The following ocurred while crawling the links: ' . $e->getMessage() );
		}

		wp_safe_redirect( wp_get_referer() );
		die();
	}

	/**
	 * Handle error
	 *
	 * @param string $message Error message.
	 */
	private static function handle_error( $message ) : void {
		$output  = '<p><b>' . esc_html__( 'Error while crawling the sitemap links.', 'wp-media-crawler' ) . '</b></p>';
		$output .= $message;
		wp_die(
			$output, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_html__( 'Sitemap Crawler Error', 'wp-media-crawler' ),
			[
				'response'  => 400,
				'back_link' => true,
			]
		);
	}
}
