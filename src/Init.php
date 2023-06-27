<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

namespace WP_Media\Crawler;

use \Symfony\Component\DomCrawler\Crawler as SymfonyCrawler;

/**
 * Class Init. Initializes the plugin and the sequential main flow.
 */
class Init {

	/**
	 * Init method.
	 */
	public static function init() {
		if ( isset( $_GET['wp_media'] ) && 'test' === $_GET['wp_media'] ) {
			$response = wp_remote_get( home_url() );

			$html = $response['body'];

			$crawler = new SymfonyCrawler( $html );

			echo '<pre>';
			print_r(
				$crawler->filterXPath( '//a/@href' )->each(
					function ( SymfonyCrawler $node, $i ) {
						return $node->text();
					}
				)
			);
			exit;
		}
	}
}
