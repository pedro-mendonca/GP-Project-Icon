<?php
/**
 * Class file for the route About.
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

if ( ! class_exists( __NAMESPACE__ . '\About' ) ) {

	/**
	 * Class About.
	 */
	class About extends Main {


		/**
		 * Route.
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		protected $route = '/tools/about/';

		/**
		 * Template.
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		protected $template = 'gptoolbox-about';
	}
}
