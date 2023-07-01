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

namespace WP_Media\Crawler\Tests\Unit\Custom\Crawlers;

use Brain\Monkey\Functions;
use \PHPUnit\Framework\TestCase;
use WP_Media\Crawler\Custom\Crawlers\LinksCrawler;
use WP_Media\Crawler\Schemas\Link;

/**
 * @covers \WP_Media\Crawler\Custom\Crawlers\LinksCrawler
 * @covers \WP_Media\Crawler\Custom\Crawlers\AbstractCrawler
 * @covers \WP_Media\Crawler\Schemas\Link
 * @group Crawlers
 */
final class LinksCrawlerTest extends TestCase {

	protected function setUp() : void {
		parent::setUp();
		\Brain\Monkey\setUp();
	}

	public function test_crawl_well_formed_links() : void {
		$mock_url = 'http://example.com';

		$this->mock_default_procedural_functions(
			$mock_url,
			'<!DOCTYPE html>
			<html>
				<body>
					<a href="http://example.com/link-1">Link 1</a>
					<a href="http://example.com/link-2">Link 2</a>
				</body>
			</html>'
		);

		$crawler = new LinksCrawler( $mock_url );
		$result  = $crawler->crawl();

		$expected_result = [
			new Link( 'Link 1', 'http://example.com/link-1' ),
			new Link( 'Link 2', 'http://example.com/link-2' ),
		];
		$this->assertEquals( $expected_result, $result );
	}

	public function test_crawl_with_links_without_title() : void {
		$mock_url = 'http://example.com';

		$this->mock_default_procedural_functions(
			$mock_url,
			'<!DOCTYPE html>
			<html>
				<body>
					<a href="http://example.com/link-1"><img src="http://example.com/img-1" /></a>
					<a href="http://example.com/link-2">Link 2</a>
				</body>
			</html>'
		);

		$crawler = new LinksCrawler( $mock_url );
		$result  = $crawler->crawl();

		$expected_result = [
			new Link( 'Link 2', 'http://example.com/link-2' ),
		];
		$this->assertEquals( $expected_result, $result );
	}

	public function test_crawl_with_links_with_title_as_attribute() : void {
		$mock_url = 'http://example.com';

		$this->mock_default_procedural_functions(
			$mock_url,
			'<!DOCTYPE html>
			<html>
				<body>
					<a href="http://example.com/link-1" title="Link 1"><img src="http://example.com/img-1" /></a>
					<a href="http://example.com/link-2">Link 2</a>
				</body>
			</html>'
		);

		$crawler = new LinksCrawler( $mock_url );
		$result  = $crawler->crawl();

		$expected_result = [
			new Link( 'Link 1', 'http://example.com/link-1' ),
			new Link( 'Link 2', 'http://example.com/link-2' ),
		];
		$this->assertEquals( $expected_result, $result );
	}

	public function test_crawl_without_links() : void {
		$mock_url = 'http://example.com';

		$this->mock_default_procedural_functions(
			$mock_url,
			'<!DOCTYPE html>
			<html>
				<body>
					<p>Not a link 1</p>
					<span>Not a link 2</span>
				</body>
			</html>'
		);

		$crawler = new LinksCrawler( $mock_url );
		$result  = $crawler->crawl();

		$this->assertIsArray( $result );
		$this->assertEmpty( $result );
	}

	public function test_crawl_with_external_links() : void {
		$mock_url = 'http://example.com';

		$this->mock_default_procedural_functions(
			$mock_url,
			'<!DOCTYPE html>
			<html>
				<body>
					<a href="http://example.com.eu/link-1">Link 1</a>
					<a href="http://example.com/link-2">Link 2</a>
				</body>
			</html>'
		);

		$crawler = new LinksCrawler( $mock_url );
		$result  = $crawler->crawl();

		$expected_result = [
			new Link( 'Link 2', 'http://example.com/link-2' ),
		];
		$this->assertEquals( $expected_result, $result );
	}

	public function test_crawl_with_no_domain_links() : void {
		$mock_url = 'http://example.com';

		$this->mock_default_procedural_functions(
			$mock_url,
			'<!DOCTYPE html>
			<html>
				<body>
					<a href="/link-1">Link 1</a>
					<a href="#test-2">Link 2</a>
				</body>
			</html>'
		);

		$crawler = new LinksCrawler( $mock_url );
		$result  = $crawler->crawl();

		$expected_result = [
			new Link( 'Link 1', '/link-1' ),
			new Link( 'Link 2', '#test-2' ),
		];
		$this->assertEquals( $expected_result, $result );
	}

	private function mock_default_procedural_functions( $mock_url, $response_body ) : void {
		Functions\expect( 'wp_remote_get' )
			->once()
			->with( $mock_url )
			->andReturn(
				[
					'body' => $response_body,
				]
			);

		Functions\expect( 'wp_parse_url' )
			->andReturnUsing(
				function( $url ) {
					return parse_url( $url, PHP_URL_HOST );
				}
			);

		Functions\expect( 'get_site_url' )
			->andReturn( $mock_url );
	}

	protected function tearDown() : void {
		\Brain\Monkey\tearDown();
		parent::tearDown();
	}
}
