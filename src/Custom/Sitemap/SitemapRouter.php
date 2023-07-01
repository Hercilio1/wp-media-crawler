<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

namespace WP_Media\Crawler\Custom\Sitemap;

/**
 * Class SitemapRouter. Serves the sitemap.html in a friendly way.
 *
 * Based on Yoast sitemaps router.
 */
final class SitemapRouter {

	/**
	 * Init method.
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'add_rewrite_rule' ], 1 );
		add_action( 'pre_get_posts', [ __CLASS__, 'redirect' ], 1 );
	}

	/**
	 * Sets up rewrite rules.
	 */
	public function add_rewrite_rule() {
		global $wp;

		$wp->add_query_var( 'wp_media_sitemap' );

		add_rewrite_rule( 'sitemap\.html$', 'index.php?wp_media_sitemap=1', 'top' );
	}

	/**
	 * Redirects to the sitemap.html.
	 *
	 * @param \WP_Query $query The WP_Query instance.
	 */
	public function redirect( $query ) {
		global $wp_rewrite;
		if ( ! $query->is_main_query() ) {
			return;
		}

		$sitemap = get_query_var( 'wp_media_sitemap' );
		if ( empty( $sitemap ) ) {
			return;
		}

		// Here we could instead of getting the sitemap.html from the filesystem, we could generate
		// it from the links stored in the database. I chose to get it from the filesystem because
		// of the non functional definition.

		if ( ! SitemapFile::exists() ) {
			$query->set_404();
			status_header( 404 );
			return;
		}

		self::sitemap_open();

		echo SitemapFile::get(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		self::sitemap_close();
	}

	/**
	 * Sitemap open
	 */
	private static function sitemap_open() : void {
		if ( headers_sent() ) {
			return;
		}

		$headers = [
			'HTTP/1.1 200 OK' => 200,
			'Content-Type: text/html; charset=' . get_bloginfo( 'charset' ) => '',
		];

		/**
		 * Filters the HTTP headers before serving the sitemap.html.
		 *
		 * @param array $headers The HTTP headers that is going to be sent.
		 */
		$headers = apply_filters( 'wp_media_sitemap_http_headers', $headers );

		foreach ( $headers as $header => $status ) {
			if ( is_numeric( $status ) ) {
				header( $header, true, $status );
				continue;
			}
			header( $header, true );
		}
	}

	/**
	 * Sitemap close
	 */
	private static function sitemap_close() : void {
		remove_all_actions( 'wp_footer' );
		die();
	}
}
