<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

namespace WP_Media\Crawler;

use WP_Media\Crawler\Custom\Admin\SitemapManager\InitSitemapManager;
use WP_Media\Crawler\Custom\Sitemap\SitemapRouter;

/**
 * Class Init. Initializes the plugin and the sequential main flow.
 */
class Init {

	/**
	 * Init method.
	 */
	public static function init() {
		SitemapRouter::init();
		InitSitemapManager::init();
	}
}
