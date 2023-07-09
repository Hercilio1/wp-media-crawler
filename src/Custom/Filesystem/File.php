<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

namespace WP_Media\Crawler\Custom\Filesystem;

use RuntimeException;
use WP_Filesystem_Direct;

/**
 * Class File. Manages files in the Uploads Folder through the filesystem API.
 */
final class File {

	/**
	 * The file name.
	 *
	 * @var string $filename
	 */
	private $filename;

	/**
	 * The file path.
	 *
	 * @var string $file_path
	 */
	private $file_path;

	/**
	 * Instance of the filesystem handler.
	 *
	 * @var WP_Filesystem_Direct
	 */
	private $filesystem;

	/**
	 * Constructor method.
	 *
	 * @param string                    $filename   The file name.
	 * @param WP_Filesystem_Direct|null $filesystem Instance of the filesystem handler.
	 *
	 * @codeCoverageIgnore
	 */
	public function __construct( $filename, $filesystem = null ) {
		$this->filename  = basename( $filename );
		$this->file_path = $this->get_path();
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
	 *
	 * @codeCoverageIgnore
	 */
	private function load_wp_default_filesystem() : WP_Filesystem_Direct {
		if ( ! is_admin() && wp_doing_cron() ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		if ( ! function_exists( '\\WP_Filesystem' ) ) {
			throw new RuntimeException( 'WP_Filesystem not found.' );
		}
		if ( ! WP_Filesystem() ) {
			throw new RuntimeException( 'WP_Filesystem not loaded.' );
		}
		return new WP_Filesystem_Direct( [] );
	}

	/**
	 * Gets the file content.
	 *
	 * @return string The file content.
	 */
	private function get_path() : string {
		$upload_dir = wp_upload_dir();
		return $upload_dir['basedir'] . "/wp-media/{$this->filename}";
	}

	/**
	 * Saves the content into the filesystem file.
	 *
	 * @param string $file_content The file content.
	 */
	public function save( $file_content ) : void {
		self::maybe_create_uploads_dir();
		$this->filesystem->put_contents( $this->file_path, $file_content );
	}

	/**
	 * Maybe creates the uploads directory.
	 */
	private function maybe_create_uploads_dir() : void {
		$upload_dir = wp_upload_dir();
		if ( ! $this->filesystem->exists( $upload_dir['basedir'] . '/wp-media' ) ) {
			$this->filesystem->mkdir( $upload_dir['basedir'] . '/wp-media' );
		}
	}

	/**
	 * Check if the file exists.
	 *
	 * @return bool True if the file exists, false otherwise.
	 */
	public function exists() : bool {
		return $this->filesystem->exists( $this->file_path );
	}

	/**
	 * Gets the file content.
	 *
	 * @return string The file content.
	 */
	public function get() : string {
		return $this->filesystem->get_contents( $this->file_path );
	}

	/**
	 * Deletes the file.
	 */
	public function delete() : void {
		if ( $this->exists() ) {
			$this->filesystem->delete( $this->file_path );
		}
	}
}
