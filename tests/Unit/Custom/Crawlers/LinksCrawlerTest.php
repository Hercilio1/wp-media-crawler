<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

namespace WP_Media\Crawler\Tests\Unit\Custom\Crawlers;

use Brain\Monkey\Functions;
use \PHPUnit\Framework\TestCase;
use WP_Media\Crawler\Custom\Crawlers\LinksCrawler;

/**
 * @covers \WP_Media\Crawler\Custom\Crawlers\LinksCrawler
 * @group Crawlers
 */
final class LinksCrawlerTest extends TestCase {

	public function setUp(): void {
		parent::setUp();
		\Brain\Monkey\setUp();
	}

	public function test_crawl_well_formed_links() {
		$mock_url = 'http://example.com';

		Functions\expect( 'wp_remote_get' )
			->once()
			->with($mock_url)
			->andReturn(array(
				'body' => <<<'HTML'
				<!DOCTYPE html>
				<html>
					<body>
						<a href="http://example.com/link-1">Link 1</a>
						<a href="http://example.com/link-2">Link 2</a>
					</body>
				</html>
				HTML
			));

		Functions\expect( 'wp_parse_url' )
			->twice()
			->andReturnUsing(
				function( $url ) {
					return parse_url( $url, PHP_URL_HOST );
				}
			);

		Functions\expect( 'get_site_url' )
			->twice()
			->andReturn( $mock_url );

		$crawler = new LinksCrawler( $mock_url );
		$result = $crawler->crawl();

		$expected_result = array(
			array(
				'title' => 'Link 1',
				'href'  => 'http://example.com/link-1',
			),
			array(
				'title' => 'Link 2',
				'href'  => 'http://example.com/link-2',
			),
		);
		$this->assertEquals( $expected_result, $result );
	}

	public function tearDown(): void {
		\Brain\Monkey\tearDown();
		parent::tearDown();
	}
}
