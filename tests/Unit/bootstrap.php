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

define( 'WP_MEDIA_TESTS_FIXTURES_DIR', dirname( __DIR__ ) . '/Fixtures' );

if ( file_exists( dirname( dirname( __DIR__ ) ) . '/vendor/autoload.php' ) ) {
	require_once dirname( dirname( __DIR__ ) ) . '/vendor/autoload.php';
}

$loader = new \Composer\Autoload\ClassLoader();
$loader->addPsr4( 'WP_Media\\Crawler\\Tests\\', dirname( __DIR__ ), true );
$loader->register();
