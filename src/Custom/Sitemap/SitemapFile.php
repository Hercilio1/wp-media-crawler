<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

namespace WP_Media\Crawler\Custom\Sitemap;

/**
 * Class SitemapFile. Manages the sitemap.html file.
 */
final class SitemapFile {

	/**
	 * Saves the sitemap into a sitemap.html file.
	 *
	 * @param string $sitemap_html The sitemap.html structure.
	 */
	public static function save( $sitemap_html ) : void {
		$upload_dir = wp_upload_dir();
		self::maybe_create_sitemap_dir( $upload_dir );

		$filename = $upload_dir['basedir'] . '/wp-media/sitemap.html';
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
		file_put_contents( $filename, $sitemap_html );
	}

	/**
	 * Maybe creates the sitemap directory.
	 *
	 * @param array $upload_dir The upload directory.
	 */
	private static function maybe_create_sitemap_dir( $upload_dir ) : void {
		if ( ! file_exists( $upload_dir['basedir'] . '/wp-media' ) ) {
			wp_mkdir_p( $upload_dir['basedir'] . '/wp-media' );
		}
	}

	/**
	 * Check if sitemap.html exists.
	 *
	 * @return bool True if sitemap.html exists, false otherwise.
	 */
	public static function exists() : bool {
		$upload_dir = wp_upload_dir();
		$filename   = $upload_dir['basedir'] . '/wp-media/sitemap.html';
		return file_exists( $filename );
	}

	/**
	 * Gets the sitemap.html content.
	 *
	 * @return string The sitemap.html content.
	 */
	public static function get() : string {
		$upload_dir = wp_upload_dir();
		$filename   = $upload_dir['basedir'] . '/wp-media/sitemap.html';
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		return file_get_contents( $filename );
	}
}
