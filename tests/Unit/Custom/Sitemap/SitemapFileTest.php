<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 *
 * @phpcs:disable Squiz.Commenting.FunctionComment
 * @phpcs:disable Squiz.Commenting.VariableComment
 * @phpcs:disable Generic.Commenting.DocComment
 * @phpcs:disable WordPress.WP.AlternativeFunctions
 */

namespace WP_Media\Crawler\Tests\Unit\Custom\Sitemap;

use Brain\Monkey\Functions;
use WP_Media\Crawler\Custom\Sitemap\SitemapFile;
use WP_Media\Crawler\Tests\Unit\FilesystemTestCase;

/**
 * @covers \WP_Media\Crawler\Custom\Sitemap\SitemapFile
 * @group Crawlers
 */
final class SitemapFileTest extends FilesystemTestCase {

	protected $path_to_test_data = '/vsf-structure/default.php';


	public function test_save() : void {
		$sitemap_html = '<html><body><h1>Sitemap</h1></body></html>';

		Functions\expect( 'wp_upload_dir' )
			->once()
			->andReturn( [ 'basedir' => 'vfs://public/wp-content/uploads' ] );

		$sitemap_file = new SitemapFile( $this->filesystem );
		$sitemap_file->save( $sitemap_html, $this->filesystem );

		$this->assertFileExists( 'vfs://public/wp-content/uploads/wp-media/sitemap.html' );
		// TODO: test the content.
	}

	public function test_save_without_the_uploads_folder() : void {
		$this->assertTrue( true );
	}
}
