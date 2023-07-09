<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 *
 * @phpcs:disable Generic.Commenting.DocComment
 * @phpcs:disable Squiz.Commenting.FunctionComment
 */

namespace WP_Media\Crawler\Tests\Integration;

use Yoast\WPTestUtils\WPIntegration\TestCase;

/**
 * @group Activation
 */
class TestPluginActivation extends TestCase {

	public function testLoadingInitClass() {
		$this->assertTrue( class_exists( 'WP_Media\Crawler\Init' ) );
	}
}
