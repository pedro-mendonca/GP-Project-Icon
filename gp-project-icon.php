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
 * GitHub Plugin URI: https://github.com/pedro-mendonca/GP-Project-Icon
 * Primary Branch:    main
 * Description:       This GlotPress plugin allows you to add icons to your projects.
 * Version:           1.0.0
 * Requires at least: 5.3
 * Tested up to:      6.5
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

// Set the plugin Rest API namespace.
// define( 'GP_PROJECT_ICON_REST_NAMESPACE', 'gp-project-icon/v1' );


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
	new Init();
}
add_action( 'gp_init', __NAMESPACE__ . '\gp_project_icon_init' );
/*
add_action( 'gp_head', function() {
	do_action( 'wp_head' );
} );

add_action( 'gp_footer', function() {
	do_action( 'wp_footer' );
} );
*/
//add_action( 'gp_head', __NAMESPACE__ . '\load_head_things' );
//add_action( 'gp_footer', __NAMESPACE__ . '\load_footer_things' );
/*
function load_head_things() {
	do_action( 'wp_head' );
}

function load_footer_things() {
	do_action( 'wp_footer' );
}
*/
//wp_enqueue_media();
/*

add_action( 'gp_init', function() {





	wp_enqueue_media();

	//add_action( 'gp_footer', 'wp_print_media_templates' );

	//wp_dequeue_style( 'global-styles' );


	//add_filter( 'wp_lazy_loading_enabled', '__return_false' );

	// Depedencies.

	//wp_enqueue_style( 'media-upload' );

	//wp_enqueue_script('media-grid'); // ERROR.
	//wp_enqueue_script( 'media-audiovideo' );
		//wp_enqueue_script('media-editor');

			// wp_enqueue_script('media-views');
				 // wp_enqueue_script('media-models');
				 // wp_enqueue_style('wp-plupload');
				 // wp_enqueue_script('wp-mediaelement');


	global $wp_scripts;
	//var_dump( $wp_scripts );

	$result = [];
    $result['scripts'] = [];
    $result['styles'] = [];

	// Print all loaded Scripts
    global $wp_scripts;
    foreach( $wp_scripts->queue as $script ) :
        $result['scripts'][$wp_scripts->registered[$script]->handle] =  $wp_scripts->registered[$script]->src . ";";
    endforeach;

    // Print all loaded Styles (CSS)
    global $wp_styles;
    foreach( $wp_styles->queue as $style ) :
        $result['styles'][$wp_styles->registered[$style]->handle] =  $wp_styles->registered[$style]->src . ";";
    endforeach;

	//var_dump( $result );
}, 0 );
*/

//require ABSPATH . WPINC . '/script-loader.php';
//add_action( 'gp_footer', 'wp_print_media_templates' );
