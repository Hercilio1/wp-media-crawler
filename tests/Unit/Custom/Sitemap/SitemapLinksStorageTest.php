<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 *
 * @phpcs:disable Squiz.Commenting.FunctionComment
 * @phpcs:disable Generic.Commenting.DocComment
 * @phpcs:disable WordPress.WP.AlternativeFunctions
 */

namespace WP_Media\Crawler\Tests\Unit\Custom\Sitemap;

use Brain\Monkey\Functions;
use \PHPUnit\Framework\TestCase;
use WP_Media\Crawler\Custom\Sitemap\SitemapLinksStorage;
use WP_Media\Crawler\Schemas\Link;

/**
 * @covers \WP_Media\Crawler\Custom\Sitemap\SitemapLinksStorage
 * @group Crawlers
 */
final class SitemapLinksStorageTest extends TestCase {

	protected function setUp() : void {
		parent::setUp();
		\Brain\Monkey\setUp();
	}

	public function test_store() : void {
		$links = [
			new Link( 'Link 1', 'http://example.com/link-1' ),
			new Link( 'Link 2', 'http://example.com/link-2' ),
		];

		$expect_stored_object = [
			'updated_at' => time(),
			'links'      => [
				[
					'title' => 'Link 1',
					'href'  => 'http://example.com/link-1',
				],
				[
					'title' => 'Link 2',
					'href'  => 'http://example.com/link-2',
				],
			],
		];

		$mock_time_method = $this->getMockBuilder( 'stdClass' )
			->addMethods( [ 'time' ] )
			->getMock();
		$mock_time_method->expects( $this->any() )
			->method( 'time' )
			->willReturn( $expect_stored_object['updated_at'] );

		Functions\expect( 'update_option' )
			->once()
			->with( 'wp_media_crawler_sitemap_links', $expect_stored_object );

		SitemapLinksStorage::store( $links );

		$this->assertTrue( true );
	}

	public function test_retrieve() : void {
		$stored_object = [
			'updated_at' => time(),
			'links'      => [
				[
					'title' => 'Link 1',
					'href'  => 'http://example.com/link-1',
				],
				[
					'title' => 'Link 2',
					'href'  => 'http://example.com/link-2',
				],
			],
		];

		Functions\expect( 'get_option' )
			->once()
			->with( 'wp_media_crawler_sitemap_links', [] )
			->andReturn( $stored_object );

		$links = SitemapLinksStorage::retrieve();

		$expected_links = [
			'updated_at' => time(),
			'links'      => [
				new Link( 'Link 1', 'http://example.com/link-1' ),
				new Link( 'Link 2', 'http://example.com/link-2' ),
			],
		];

		$this->assertEquals( $expected_links, $links );
	}

	public function test_retrieve_of_bad_stored_links() : void {
		$stored_object = [
			[
				'title' => 'Link 1',
			],
			[
				'titles' => 'Link 2',
				'uri'    => 'http://example.com/link-2',
			],
		];

		$expected_links = [];

		Functions\expect( 'get_option' )
			->once()
			->with( 'wp_media_crawler_sitemap_links', [] )
			->andReturn( $stored_object );

		$links = SitemapLinksStorage::retrieve();

		$this->assertEquals( $expected_links, $links );
	}

	public function test_delete() : void {
		Functions\expect( 'delete_option' )
			->once()
			->with( 'wp_media_crawler_sitemap_links' );

		SitemapLinksStorage::delete();

		$this->assertTrue( true );
	}

	protected function tearDown() : void {
		\Brain\Monkey\tearDown();
		parent::tearDown();
	}
}
