<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

namespace WP_Media\Crawler\Custom\Sitemap;

use WP_Media\Crawler\Schemas\Link;

/**
 * Class SitemapLinksStorage. Handle the CRUD of the sitemaps links.
 */
final class SitemapLinksStorage {

	/**
	 * Stores the sitemap links.
	 *
	 * @param Link[] $links The links to be stored.
	 */
	public static function store( $links ) : void {
		$links = array_map(
			function( $link ) {
				return (array) $link;
			},
			$links
		);
		update_option(
			'wp_media_crawler_sitemap_links',
			[
				'updated_at' => time(),
				'links'      => $links,
			]
		);
	}

	/**
	 * Retrieves the sitemap links.
	 *
	 * @return array The sitemap links.
	 */
	public static function retrieve() : array {
		$stored_links = get_option( 'wp_media_crawler_sitemap_links', [] );

		if ( ! isset( $stored_links['links'] ) || ! isset( $stored_links['updated_at'] ) ) {
			return [];
		}

		if ( is_array( $stored_links['links'] ) ) {
			$links = [];
			foreach ( $stored_links['links'] as $link ) {
				if ( isset( $link['title'] ) && isset( $link['href'] ) ) {
					$links[] = new Link( $link['title'], $link['href'] );
				}
			}
			$stored_links['links'] = $links;
		} else {
			$stored_links['links'] = [];
		}

		return $stored_links;
	}

	/**
	 * Deletes the sitemap links.
	 */
	public static function delete() : void {
		delete_option( 'wp_media_crawler_sitemap_links' );
	}
}
