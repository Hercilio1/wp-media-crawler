<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

namespace WP_Media\Crawler\Custom\Admin\SitemapManager;

/**
 * Class InitSitemapManager.
 */
final class InitSitemapManager {

	/**
	 * Init method.
	 */
	public static function init() : void {
		OptionsPage::register();
		CrawlLinksHandler::init();
	}
}
