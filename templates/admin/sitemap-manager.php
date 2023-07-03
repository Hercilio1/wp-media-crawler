<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

/**
 * Attributes of the template.
 *
 * @var \WP_Media\Crawler\Schemas\LinksRecord $wp_media_sitemap_links - The links Record.
 */

list(
	'sitemap_links' => $wp_media_sitemap_links,
) = $args;

?>

<div class="wrap">
	<h1>WP Media | Sitemap Manager</h1>
	<p><?php esc_html_e( 'This page allows you to manage and analyze the website sitemap.', 'wp-media-crawler' ); ?></p>
	<hr>
	<form method="POST" action="admin-post.php">
		<button type="submit" class="button button-primary">Crawl Sitemap Links</button>
		<input type="hidden" name="action" value="wp_media_crawler_crawl_sitemap_links">
		<?php wp_nonce_field( 'wp_media_crawler_crawl_sitemap_links' ); ?>
	</form>

	// TODO: Add overview showing the options of checking out the sitemap.html or to crawl the links if there is no sitemap.html.
	<br>
	// TODO: Add overview of the cronjob.
	<hr>

	<h2><?php esc_html_e( 'Sitemap Links', 'wp-media-crawler' ); ?></h2>

	<?php if ( $wp_media_sitemap_links ) : ?>
		<p><?php esc_html_e( 'The following table shows the links found by the sitemap crawler.', 'wp-media-crawler' ); ?></p>
		<p>
		<?php
		echo esc_html(
			sprintf(
				/* translators: %s: the timestamp */
				__( 'The last crawl happened at: %s', 'wp-media-crawler' ),
				$wp_media_sitemap_links->get_formatted_timestamp()
			)
		);
		?>
		</p>
		<table class="wp-list-table widefat fixed striped" style="max-width: 1000px">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Link Title', 'wp-media-crawler' ); ?></th>
					<th><?php esc_html_e( 'Link URL', 'wp-media-crawler' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $wp_media_sitemap_links->links as $wp_media_sitemap_link ) : ?>
					<tr>
						<td><?php echo esc_html( $wp_media_sitemap_link->title ); ?></td>
						<td>
							<a href="<?php echo esc_url( $wp_media_sitemap_link->href ); ?>"
								title="<?php echo esc_attr( $wp_media_sitemap_link->title ); ?>">
								<?php echo esc_html( $wp_media_sitemap_link->get_href_with_domain() ); ?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else : ?>
		// TODO: Add message when there is not links stored.
		<br>
		<p><?php esc_html_e( 'There are no links stored.', 'wp-media-crawler' ); ?></p>
	<?php endif; ?>
</div>
