<?php
/**
 * GP Project Icon
 *
 * @package           GP_Project_Icon
 * @link              https://github.com/pedro-mendonca/GP-Project-Icon
 * @author            Pedro Mendonça
 * @copyright         2024 Pedro Mendonça
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       GP Project Icon
 * Plugin URI:        https://wordpress.org/plugins/gp-project-icon/
 * Description:       This GlotPress plugin allows you to add icons to your projects.
 * Version:           1.0.1
 * Requires at least: 5.3
 * Tested up to:      6.8
 * Requires PHP:      7.4
 * Requires Plugins:  glotpress
 * Author:            Pedro Mendonça
 * Author URI:        https://profiles.wordpress.org/pedromendonca/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       gp-project-icon
 * Domain Path:       /languages
 */

namespace GP_Project_Icon;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// Check if get_plugin_data() function exists.
if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// Get the plugin headers data.
$gp_project_icon_data = get_plugin_data( __FILE__, false, false );


// Set the plugin version.
if ( ! defined( 'GP_PROJECT_ICON_VERSION' ) ) {
	define( 'GP_PROJECT_ICON_VERSION', $gp_project_icon_data['Version'] );
}

// Set the plugin required PHP version. Needed for PHP compatibility check for WordPress < 5.1.
if ( ! defined( 'GP_PROJECT_ICON_REQUIRED_PHP' ) ) {
	define( 'GP_PROJECT_ICON_REQUIRED_PHP', $gp_project_icon_data['RequiresPHP'] );
}

// Set the plugin URL.
define( 'GP_PROJECT_ICON_DIR_URL', plugin_dir_url( __FILE__ ) );

// Set the plugin filesystem path.
define( 'GP_PROJECT_ICON_DIR_PATH', plugin_dir_path( __FILE__ ) );

// Set the plugin file path.
define( 'GP_PROJECT_ICON_FILE', plugin_basename( __FILE__ ) );


// Include Composer autoload.
require_once GP_PROJECT_ICON_DIR_PATH . 'vendor/autoload.php';

/**
 * Initialize the plugin.
 *
 * @since 1.0.0
 *
 * @return void
 */
function gp_project_icon_init() {
	new Project_Icon();
}
add_action( 'gp_init', __NAMESPACE__ . '\gp_project_icon_init' );
