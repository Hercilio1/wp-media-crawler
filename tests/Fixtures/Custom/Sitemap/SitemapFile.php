<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

return [
	'structure' => [
		'wp-content' => [
			'uploads'                  => [
				'wp-media' => [],
			],
			'uploads-no-folder-test'   => [
				'.' => [],
			],
			'uploads-file-exists-test' => [
				'wp-media' => [
					'sitemap.html' => '<html><body><h1>Sitemap</h1></body></html>',
				],
			],
		],
	],
];
