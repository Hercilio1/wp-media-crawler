<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 *
 * @phpcs:disable WordPress.NamingConventions.PrefixAllGlobals
 */

/**
 * File that will be run by PHPUnit before testings
 */

define( 'WP_MEDIA_PLUGIN_ROOT', dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR );
define( 'WP_MEDIA_PLUGIN_TESTS_ROOT', dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'Integration' . DIRECTORY_SEPARATOR );
define( 'WP_MEDIA_TESTS_FIXTURES_DIR', dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'Fixtures' );

// Load the Composer autoloader.
require_once WP_MEDIA_PLUGIN_ROOT . 'vendor/autoload.php';
require_once WP_MEDIA_PLUGIN_ROOT . 'vendor/yoast/wp-test-utils/src/WPIntegration/Autoload.php';

/**
 * Get the WordPress' tests suite directory.
 *
 * @return string Returns The directory path to the WordPress testing environment.
 */
function get_wp_tests_dir() {
	$tests_dir = getenv( 'WP_TESTS_DIR' );

	// Travis CI & Vagrant SSH tests directory.
	if ( empty( $tests_dir ) ) {
		$tests_dir = '/tmp/wordpress-tests-lib';
	}

	// If the tests' includes directory does not exist, try a relative path to Core tests directory.
	if ( ! file_exists( $tests_dir . '/includes/' ) ) {
		$tests_dir = '../../../../tests/phpunit';
	}

	// Check it again. If it doesn't exist, stop here and post a message as to why we stopped.
	if ( ! file_exists( $tests_dir . '/includes/' ) ) {
		trigger_error( 'Unable to run the integration tests, because the WordPress test suite could not be located.', E_USER_ERROR ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error -- Valid use case for our testing suite.
	}

	// Strip off the trailing directory separator, if it exists.
	return rtrim( $tests_dir, DIRECTORY_SEPARATOR );
}

/**
 * Bootstraps the integration testing environment with WordPress and Imagify.
 *
 * @param string $wp_tests_dir The directory path to the WordPress testing environment.
 */
function bootstrap_integration_suite( $wp_tests_dir ) {
	// Give access to tests_add_filter() function.
	require_once $wp_tests_dir . '/includes/functions.php';

	// Manually load the plugin being tested.
	tests_add_filter(
		'muplugins_loaded',
		function() {
			// Load the plugin.
			require WP_MEDIA_PLUGIN_ROOT . '/wp-media-crawler.php';
		}
	);

	// Start up the WP testing environment.
	require_once $wp_tests_dir . '/includes/bootstrap.php';
}

bootstrap_integration_suite( get_wp_tests_dir() );

/**
 * The following structure is needed to load the Tests classes. For some reason the autoloader
 * loading some classes from the plugin and some dependencies tests folders.
 */

$loader = new \Composer\Autoload\ClassLoader();
$loader->addPsr4( '\\WP_Media\\Crawler\\Tests\\', dirname( __DIR__ ), true );
$loader->register();

Yoast\WPTestUtils\WPIntegration\Autoload::load( 'Yoast\\WPTestUtils\\WPIntegration\\TestCase' );
