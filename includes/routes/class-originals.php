<?php
/**
 * Class file for the route Originals.
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

if ( ! class_exists( __NAMESPACE__ . '\Originals' ) ) {

	/**
	 * Class Originals.
	 */
	class Originals extends Main {


		/**
		 * Route.
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		protected $route = '/tools/originals/';

		/**
		 * Template.
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		protected $template = 'gptoolbox-originals';
	}
}
