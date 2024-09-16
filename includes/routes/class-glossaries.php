<?php
/**
 * Class file for the route Glossaries.
 *
 * @package GP_Project_Icon
 *
 * @since 1.0.0
 */

namespace GP_Project_Icon\Routes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( __NAMESPACE__ . '\Glossaries' ) ) {

	/**
	 * Class Glossaries.
	 */
	class Glossaries extends Main {


		/**
		 * Route.
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		protected $route = '/tools/glossaries/';

		/**
		 * Template.
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		protected $template = 'gptoolbox-glossaries';
	}
}
