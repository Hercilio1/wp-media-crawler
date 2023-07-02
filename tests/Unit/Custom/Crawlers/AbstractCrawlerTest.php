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
use Mockery;
use \PHPUnit\Framework\TestCase;
use WP_Media\Crawler\Custom\Crawlers\LinksCrawler;

/**
 * @covers \WP_Media\Crawler\Custom\Crawlers\AbstractCrawler
 * @group Crawlers
 */
final class AbstractCrawlerTest extends TestCase {

	protected function setUp() : void {
		parent::setUp();
		\Brain\Monkey\setUp();
	}

	public function test_request_exception() : void {
		$mock_url  = 'http://example.com';
		$error_msg = 'Error while downloading the home page HTML.';

		$this->expectException( \Exception::class );
		$this->expectExceptionMessage( $error_msg );

		Mockery::mock( 'overload:WP_Error' );

		Functions\expect( 'wp_remote_get' )
			->with( $mock_url )
			->andReturn( new \WP_Error( 'error', 'error' ) );

		Functions\expect( '__' )
			->once()
			->andReturn( $error_msg );

		$crawler = new LinksCrawler( $mock_url );
		$crawler->crawl();
	}

	public function test_response_body_exception() : void {
		$mock_url  = 'http://example.com';
		$error_msg = 'The homepage HTML is improperly formatted.';

		$this->expectException( \Exception::class );
		$this->expectExceptionMessage( $error_msg );

		Mockery::mock( 'overload:WP_Error' );

		Functions\expect( 'wp_remote_get' )
			->with( $mock_url )
			->andReturn( [] );

		Functions\expect( 'wp_remote_retrieve_body' )
			->with( [] )
			->andReturn( '' );

		Functions\expect( '__' )
			->once()
			->andReturn( $error_msg );

		$crawler = new LinksCrawler( $mock_url );
		$crawler->crawl();
	}

	protected function tearDown() : void {
		\Brain\Monkey\tearDown();
		parent::tearDown();
	}
}
