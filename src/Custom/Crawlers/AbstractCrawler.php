<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

namespace WP_Media\Crawler\Custom\Crawlers;

use Exception;

/**
 * Class AbstractCrawler. Abstraction of any crawler.
 */
abstract class AbstractCrawler {

	/**
	 * Web page url
	 *
	 * @var string $url
	 */
	private $url;

	/**
	 * Constructor method.
	 *
	 * @param string $url The URL to be crawled.
	 */
	public function __construct( $url ) {
		$this->url = $url;
	}

	/**
	 * Abstract method 'crawl'. All crawlers should crawl.
	 *
	 * @return array The crawled data.
	 */
	abstract public function crawl() : array;

	/**
	 * Get the web page content
	 *
	 * @return string The web page content.
	 *
	 * @throws Exception If the page request returns an error.
	 * @throws Exception If the response body is empty.
	 */
	public function get_the_webpage_content() : string {
		$response = wp_remote_get( $this->url );

		if ( is_wp_error( $response ) ) {
			throw new Exception( __( 'Error while downloading the home page HTML.', 'wp-media-crawler' ) );
		}

		$body = wp_remote_retrieve_body( $response );
		if ( empty( $body ) ) {
			throw new Exception( __( 'The homepage HTML is improperly formatted.', 'wp-media-crawler' ) );
		}
		return $body;
	}
}
