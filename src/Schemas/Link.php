<?php
/**
 * Implemented by Hercilio M. Ortiz (https://github.com/Hercilio1).
 *
 * @package WP_Media_Crawler
 */

namespace WP_Media\Crawler\Schemas;

/**
 * Class Link. Stores the link data.
 */
class Link {

	/**
	 * The link title.
	 *
	 * @var string $title
	 */
	public $title;

	/**
	 * The link URL.
	 *
	 * @var string $href
	 */
	public $href;

	/**
	 * Constructor method.
	 *
	 * @param string $title The link title.
	 * @param string $href  The link URL.
	 */
	public function __construct( $title, $href ) {
		$this->title = $title;
		$this->href  = $href;
	}
}
