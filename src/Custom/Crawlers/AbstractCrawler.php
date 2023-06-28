<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

namespace WP_Media\Crawler\Custom\Crawlers;

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
	 */
	abstract public function crawl();

	/**
	 * Get the web page content
	 *
	 * @return string The web page content.
	 */
	protected function get_the_webpage_content() {
		$response = wp_remote_get( $this->url );
		if ( is_wp_error( $response ) ) {
			return ''; // TODO: Add exception handling.
		}
		if ( ! isset( $response['body'] ) || empty( $response['body'] ) ) {
			return ''; // TODO: Add exception handling.
		}
		return $response['body'];
	}
}
