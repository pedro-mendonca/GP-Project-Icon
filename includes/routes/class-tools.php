<?php
/**
 * Class file for the route Tools.
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

if ( ! class_exists( __NAMESPACE__ . '\Tools' ) ) {

	/**
	 * Class Tools.
	 */
	class Tools extends Main {


		/**
		 * Route.
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		protected $route = '/tools/';

		/**
		 * Template.
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		protected $template = 'gptoolbox-tools';
	}
}
