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
					'home.html' => '<html><body><h1>Home</h1></body></html>',
				],
			],
		],
	],
];
