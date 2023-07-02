<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

namespace WP_Media\Crawler\Custom\Sitemap;

use RuntimeException;
use WP_Filesystem_Direct;

/**
 * Class SitemapFile. Manages the sitemap.html file.
 */
final class SitemapFile {

	/**
	 * Instance of the filesystem handler.
	 *
	 * @var WP_Filesystem_Direct
	 */
	private $filesystem;

	/**
	 * Constructor method.
	 *
	 * @param WP_Filesystem_Direct|null $filesystem Instance of the filesystem handler.
	 *
	 * @codeCoverageIgnore
	 */
	public function __construct( $filesystem = null ) {
		if ( $filesystem ) {
			$this->filesystem = $filesystem;
		} else {
			$this->filesystem = $this->load_wp_default_filesystem();
		}
	}

	/**
	 * Loads the default WordPress filesystem.
	 *
	 * @return WP_Filesystem_Direct The default WordPress filesystem.
	 *
	 * @throws RuntimeException If WP_Filesystem not found or not loaded.
	 */
	private function load_wp_default_filesystem() : WP_Filesystem_Direct {
		if ( ! function_exists( '\\WP_Filesystem' ) ) {
			throw new RuntimeException( 'WP_Filesystem not found.' );
		}
		if ( ! WP_Filesystem() ) {
			throw new RuntimeException( 'WP_Filesystem not loaded.' );
		}
		return new WP_Filesystem_Direct( [] );
	}

	/**
	 * Saves the sitemap into a sitemap.html file.
	 *
	 * @param string $sitemap_html The sitemap.html structure.
	 */
	public function save( $sitemap_html ) : void {
		$upload_dir = wp_upload_dir();
		self::maybe_create_sitemap_dir( $upload_dir );

		$filename = $upload_dir['basedir'] . '/wp-media/sitemap.html';
		$this->filesystem->put_contents( $filename, $sitemap_html );
	}

	/**
	 * Maybe creates the sitemap directory.
	 *
	 * @param array $upload_dir The upload directory.
	 */
	private function maybe_create_sitemap_dir( $upload_dir ) : void {
		if ( ! $this->filesystem->exists( $upload_dir['basedir'] . '/wp-media' ) ) {
			$this->filesystem->mkdir( $upload_dir['basedir'] . '/wp-media' );
		}
	}

	/**
	 * Check if sitemap.html exists.
	 *
	 * @return bool True if sitemap.html exists, false otherwise.
	 */
	public function exists() : bool {
		$upload_dir = wp_upload_dir();
		$filename   = $upload_dir['basedir'] . '/wp-media/sitemap.html';
		return $this->filesystem->exists( $filename );
	}

	/**
	 * Gets the sitemap.html content.
	 *
	 * @return string The sitemap.html content.
	 */
	public function get() : string {
		$upload_dir = wp_upload_dir();
		$filename   = $upload_dir['basedir'] . '/wp-media/sitemap.html';
		return $this->filesystem->get_contents( $filename );
	}
}
