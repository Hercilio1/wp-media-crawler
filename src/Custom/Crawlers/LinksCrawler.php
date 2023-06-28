<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

namespace WP_Media\Crawler\Custom\Crawlers;

use \Symfony\Component\DomCrawler\Crawler;

/**
 * Class LinksCrawler. Crawls the links of a Web page.
 */
class LinksCrawler extends AbstractCrawler {

	/**
	 * The links list
	 *
	 * @var array $links
	 */
	private $links = [];

	/**
	 * Crawls the links of a Webpage.
	 *
	 * @return array The links found.
	 */
	public function crawl() {
		$html = $this->get_the_webpage_content();
		if ( $html ) {
			$crawler = new Crawler( $html );
			$crawler->filterXPath( '//a' )->each(
				function( Crawler $node ) {
					$this->add_link( $node );
				}
			);
		}
		return $this->links;
	}

	/**
	 * Add found link to the crawled links list if it is well formed.
	 *
	 * @param Crawler $node - The link DOM node.
	 */
	private function add_link( Crawler $node ) {
		$title = $node->text();
		$href  = $node->attr( 'href' );
		if ( ! empty( $title ) && $this->is_internal_link( $href ) ) {
			$this->links[] = [
				'title' => $title,
				'href'  => $href,
			];
		}
	}

	/**
	 * Checks if the link is internal.
	 *
	 * @param string $link - The link to check.
	 *
	 * @return bool - True if the link is internal, false otherwise.
	 */
	private function is_internal_link( $link ) {
		$internal_domain = wp_parse_url( get_site_url(), PHP_URL_HOST );
		return strpos( $link, $internal_domain ) !== false;
	}
}
