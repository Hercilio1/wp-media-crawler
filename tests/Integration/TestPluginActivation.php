<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 *
 * @phpcs:disable WordPress.NamingConventions.PrefixAllGlobals
 */

namespace WP_Media\Crawler\Tests\Integration;

use Yoast\WPTestUtils\WPIntegration\TestCase;

/**
 * @covers
 *
 * @group Admin_Only
 * @group Activation
 */
class TestPluginActivation extends TestCase {

	public function testLoadingInitClass() {
		$this->assertTrue( class_exists( 'WP_Media\Crawler\Init' ) );
	}
}
