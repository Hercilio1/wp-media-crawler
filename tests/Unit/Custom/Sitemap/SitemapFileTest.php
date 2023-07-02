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

	protected $path_to_test_data = '/Custom/Sitemap/SitemapFile.php';

	public function test_save() : void {
		$sitemap_html = '<html><body><h1>Sitemap</h1></body></html>';

		Functions\expect( 'wp_upload_dir' )
			->once()
			->andReturn( [ 'basedir' => 'vfs://public/wp-content/uploads' ] );

		$sitemap_file = new SitemapFile( $this->filesystem );
		$sitemap_file->save( $sitemap_html, $this->filesystem );

		$this->assertFileExists( 'vfs://public/wp-content/uploads/wp-media/sitemap.html' );
		$this->assertSame( $sitemap_html, $this->filesystem->get_contents( 'vfs://public/wp-content/uploads/wp-media/sitemap.html' ) );
	}

	public function test_save_without_the_uploads_folder() : void {
		$sitemap_html = '<html><body><h1>Sitemap</h1></body></html>';

		Functions\expect( 'wp_upload_dir' )
			->once()
			->andReturn( [ 'basedir' => 'vfs://public/wp-content/uploads-no-folder-test' ] );

		$sitemap_file = new SitemapFile( $this->filesystem );
		$sitemap_file->save( $sitemap_html, $this->filesystem );

		$this->assertFileExists( 'vfs://public/wp-content/uploads-no-folder-test/wp-media/sitemap.html' );
		$this->assertSame( $sitemap_html, $this->filesystem->get_contents( 'vfs://public/wp-content/uploads-no-folder-test/wp-media/sitemap.html' ) );
	}

	public function test_exists() : void {
		$basedir = 'vfs://public/wp-content/uploads-file-exists-test';

		Functions\expect( 'wp_upload_dir' )
			->once()
			->andReturn( [ 'basedir' => $basedir ] );

		$sitemap_file = new SitemapFile( $this->filesystem );
		$result       = $sitemap_file->exists( $basedir . '/wp-media/sitemap.html' );

		$this->assertTrue( $result );
	}

	public function test_do_not_exists() : void {
		$basedir = 'vfs://public/wp-content/uploads-file-not-exists-test';

		Functions\expect( 'wp_upload_dir' )
			->once()
			->andReturn( [ 'basedir' => $basedir ] );

		$sitemap_file = new SitemapFile( $this->filesystem );
		$result       = $sitemap_file->exists( $basedir . '/wp-media/sitemap.html' );

		$this->assertFalse( $result );
	}

	public function test_get_file_contents() : void {
		$basedir = 'vfs://public/wp-content/uploads-file-exists-test';

		Functions\expect( 'wp_upload_dir' )
			->once()
			->andReturn( [ 'basedir' => $basedir ] );

		$sitemap_file = new SitemapFile( $this->filesystem );
		$result       = $sitemap_file->get( $basedir . '/wp-media/sitemap.html' );

		$this->assertStringEqualsFile( $basedir . '/wp-media/sitemap.html', $result );
	}

	public function test_get_file_contents_without_file() : void {
		$basedir = 'vfs://public/wp-content/uploads-file-not-exists-test';

		Functions\expect( 'wp_upload_dir' )
			->once()
			->andReturn( [ 'basedir' => $basedir ] );

		$sitemap_file = new SitemapFile( $this->filesystem );
		$result       = $sitemap_file->get( $basedir . '/wp-media/sitemap.html' );

		$this->assertEquals( '', $result );
	}
}
