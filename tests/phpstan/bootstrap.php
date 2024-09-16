<?php
/**
 * PHPStan bootstrap file
 *
 * @package GP_Project_Icon
 */

// Set script debug.
if ( ! defined( 'SCRIPT_DEBUG' ) ) {
	define( 'SCRIPT_DEBUG', true );
}

// Set plugin version.
if ( ! defined( 'GP_PROJECT_ICON_VERSION' ) ) {
	define( 'GP_PROJECT_ICON_VERSION', '1.2.5' );
}

// Set plugin required PHP version. Needed for PHP compatibility check for WordPress < 7.2.
if ( ! defined( 'GP_PROJECT_ICON_REQUIRED_PHP' ) ) {
	define( 'GP_PROJECT_ICON_REQUIRED_PHP', '7.4' );
}


// Require plugin main file.
require_once 'gp-project-icon.php';
