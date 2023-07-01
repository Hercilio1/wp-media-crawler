<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

/**
 * Attributes of the template.
 *
 * @var string $wp_media_links_wrapper The links wrapper.
 */

list(
	'links_wrapper' => $wp_media_links_wrapper,
) = $args;

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<title><?php bloginfo( 'name' ); ?> | Sitemap</title>
</head>
<body>
	<h1>Sitemap</h1>
	<?php
	echo wp_kses(
		$wp_media_links_wrapper,
		[
			'div' => [
				'class' => [],
			],
			'ul'  => [],
			'li'  => [],
			'a'   => [
				'href'  => [],
				'title' => [],
			],
		]
	);
	?>
</body>
</html>
