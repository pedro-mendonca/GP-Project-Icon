<?php
/**
 * Class file for the Init.
 *
 * @package GP_Project_Icon
 *
 * @since 1.0.0
 */

namespace GP_Project_Icon;

use GP;
use GP_Locale;
use GP_Locales;
use GP_Project;
use GP_Translation;
use GP_Translation_Set;
use WP_Error;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( __NAMESPACE__ . '\Init' ) ) {

	/**
	 * Class Init.
	 */
	class Init {


		/**
		 * Registers actions.
		 *
		 * @return void
		 */
		public function __construct() {

			// Add project icon on Project header.
			// add_action( 'gp_post_tmpl_load', array( self::class, 'project_header_icon' ) );

			// Add Project Icon field to the form.
			add_action( 'gp_after_project_form_fields', array( self::class, 'project_form_icon' ) );

			// Add project icon on project created.
			add_action( 'gp_project_created', array( self::class, 'update_project_icon' ) );

			// Update project icon on project updated.
			add_action( 'gp_project_saved', array( self::class, 'update_project_icon' ) );

			// Delete project icon on project deleted.
			add_action( 'gp_project_deleted', array( self::class, 'delete_project_icon' ) );

			// Register and enqueue plugin style sheet.
			self::register_scripts();

			// Load things before templates.
			// add_action( 'gp_pre_tmpl_load', array( self::class, 'pre_template_load' ), 10, 2 );

			//add_action( 'gp_footer', array( self::class, 'media_selector_print_scripts' ), 10, 2 );


			// Load things after templates.
			add_action( 'gp_post_tmpl_load', array( self::class, 'post_template_load' ), 10, 2 );

/*
			// Register extra GlotPress routes.
			add_action( 'template_redirect', array( $this, 'register_gp_routes' ), 5 );

			// Set template locations.
			add_filter( 'gp_tmpl_load_locations', array( $this, 'template_load_locations' ), 10, 4 );

			// Instantiate Rest API.
			new Rest_API();
			*/
		}

		public static function media_selector_print_scripts() {

			$my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );

			?><script type='text/javascript'>
				/* global  document, Intl, gpToolbox, wp, wpApiSettings */

				jQuery( document ).ready( function( $ ) {

					// Uploading files
					var file_frame;
					var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
					var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this

					jQuery('#upload_image_button').on('click', function( event ){

						event.preventDefault();

						// If the media frame already exists, reopen it.
						if ( file_frame ) {
							// Set the post ID to what we want
							file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
							// Open frame
							file_frame.open();
							return;
						} else {
							// Set the wp.media post id so the uploader grabs the ID we want when initialised
							wp.media.model.settings.post.id = set_to_post_id;
						}

						// Create the media frame.
						file_frame = wp.media.frames.file_frame = wp.media({
							title: 'Select a image to upload',
							button: {
								text: 'Use this image',
							},
							multiple: false	// Set to true to allow multiple files to be selected
						});

						// When an image is selected, run a callback.
						file_frame.on( 'select', function() {
							// We set multiple to false so only get one image from the uploader
							attachment = file_frame.state().get('selection').first().toJSON();

							// Do something with attachment.id and/or attachment.url here
							$( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
							$( '#image_attachment_id' ).val( attachment.id );

							// Restore the main post ID
							wp.media.model.settings.post.id = wp_media_post_id;
						});

							// Finally, open the modal
							file_frame.open();
					});

					// Restore the main ID when the add media button is pressed
					jQuery( 'a.add_media' ).on( 'click', function() {
						wp.media.model.settings.post.id = wp_media_post_id;
					});
				});

			</script><?php

		}


		public static function project_header_icon( $project ) {

			// Get existent project icon.
			$project_icon = self::get_project_icon( $project );
			if ( $project_icon ) {
				// Project has icon, show icon image.

			} else {
				// Project has no icon, show icon placeholder.

			}

			?>
			<div class='image-preview-wrapper'>
				<img id='image-preview' src='<?php echo wp_get_attachment_url( $project_icon ); ?>' height='100'>
			</div>

			<?php
		}

		public static function project_form_icon( $project ) {

			//var_dump( $project );

			// Get existent project icon.
			$project_icon = self::get_project_icon( $project );
			if ( $project_icon ) {
				// Project has icon, show icon image.

			} else {
				// Project has no icon, show icon placeholder.

			}

			?>
			<div class='image-preview-wrapper'>
				<img id='image-preview' src='<?php echo wp_get_attachment_url( $project_icon ); ?>' height='100'>
			</div>

			<dt><label for="project[icon]"><?php esc_html_e( 'Icon', 'gp-project-icon' ); ?></label></dt>
			<dd><input type="text" name="project[icon]" value="<?php echo esc_html( $project_icon ); ?>" id="project[icon]"></dd>
			<?php
						/*

			$project_icon = esc_attr( self::get_project_icon( $project ) );

			?>
			<p class="project-icon">
				<label for="project-icon"><?php esc_html_e( 'Project Icon', 'gp-project-icon' ); ?></label><br />
				<input type="hidden" name="project_icon" id="project-icon" value="<?php echo 'PEDRO'; //echo $project_icon; ?>" />
				<img id="project-icon-preview" src="<?php //echo $project_icon; ?>" style="max-width: 100px; max-height: 100px;" /><br />
				<input type="button" class="button" id="project-icon-button" value="<?php _e( 'Select Icon', 'glotpress' ); ?>" />
				<input type="button" class="button" id="project-icon-remove" value="<?php _e( 'Remove Icon', 'glotpress' ); ?>" />

				<!--<button class="custom_media_upload_button" />-->

				<?php echo do_shortcode('[frontend-button]'); ?>
			</p>

			<?php
			*/
			/*

			// Save attachment ID
			if ( isset( $_POST['submit_image_selector'] ) && isset( $_POST['image_attachment_id'] ) ) :
				update_option( 'media_selector_attachment_id', absint( $_POST['image_attachment_id'] ) );
			endif;

			wp_enqueue_media();
			*/
			/*
			?>

			<div class='image-preview-wrapper'>
				<img id='image-preview' src='<?php echo wp_get_attachment_url( get_option( 'media_selector_attachment_id' ) ); ?>' height='100'>
			</div>
			<input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload image' ); ?>" />
			<input type='hidden' name='image_attachment_id' id='image_attachment_id' value='<?php echo get_option( 'media_selector_attachment_id' ); ?>'>
			<?php

			*/



		}


		/**
		 * Get project icon.
		 *
		 * @since 1.0.0
		 *
		 * @param GP_Project $project   GlotPress Project.
		 *
		 * @return mixed|false    Metadata or false.
		 */
		public static function get_project_icon( $project ) {

			return gp_get_meta( 'project', $project->id, 'project_icon' );
		}


		/**
		 * Update project icon.
		 *
		 * @since 1.0.0
		 *
		 * @param GP_Project $project   GlotPress Project.
		 *
		 * @return bool   Return 'true' if updated successfully, 'false' if not.
		 */
		public static function update_project_icon( $project ) {

			// TODO: Sanitize.
			$project_icon = $_POST['project']['icon'];

			return gp_update_meta( $project->id, 'project_icon', $project_icon, 'project' );
		}


		/**
		 * Delete project icon.
		 *
		 * @since 1.0.0
		 *
		 * @param GP_Project $project   GlotPress Project.
		 *
		 * @return bool   Return 'true' if deleted successfully, 'false' if not.
		 */
		public static function delete_project_icon( $project ) {

			return gp_delete_meta( $project->id, 'project_icon', null, 'project' );
		}


		/**
		 * Get GP-Project-Icon templates.
		 *
		 * @since 1.0.0
		 *
		 * @param array<int, string> $locations     File paths of template locations.
		 * @param string             $template      The template name.
		 * @param array<mixed>       $args          Arguments passed to the template.
		 * @param string|null        $template_path Priority template location, if any.
		 *
		 * @return array<int, string>   Template locations.
		 */
		public function template_load_locations( $locations, $template, $args, $template_path ) {

			unset( $args, $template_path );

			// Register and enqueue scripts for Tools.
			$template_prefix = 'gptoolbox-';

			// Check GP Project Icon templates prefix.
			if ( substr( $template, 0, strlen( $template_prefix ) ) === $template_prefix ) {
				$locations = array(
					GP_PROJECT_ICON_DIR_PATH . 'gp-templates/',
				);
			}

			return $locations;
		}


		/**
		 * Check if GlotPress is activated.
		 *
		 * @since 1.0.0
		 *
		 * @return bool
		 */
		public static function check_gp() {

			if ( ! class_exists( 'GP' ) ) {
				add_action( 'admin_notices', array( self::class, 'notice_gp_not_found' ) );
				return false;
			}

			return true;
		}


		/**
		 * Render GlotPress not found admin notice.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public static function notice_gp_not_found() {

			?>
			<div class="notice notice-error is-dismissible">
				<p>
					<?php
					printf(
						/* translators: 1: Plugin name. 2: Error message. */
						esc_html__( '%1$s: %2$s', 'gp-project-icon' ),
						'<b>' . esc_html_x( 'GP Project Icon', 'Plugin name', 'gp-project-icon' ) . '</b>',
						esc_html__( 'GlotPress not found. Please install and activate it.', 'gp-project-icon' )
					);
					?>
				</p>
			</div>
			<?php
		}


		/**
		 * Load things after templates.
		 *
		 * @since 1.0.0
		 *
		 * @param string               $template   The template name.
		 * @param array<string,string> $args       Arguments passed to the template.
		 *
		 * @return void
		 */
		public static function pre_template_load( $template, &$args ) {

			wp_enqueue_media();

			// https://gist.github.com/bcole808/b31ed5101b1723e77dce
			// https://jeroensormani.com/how-to-include-the-wordpress-media-selector-in-your-plugin/

			if ( $template === 'project-new' || $template === 'project-edit' ) {



				// Check if SCRIPT_DEBUG is true.
				$suffix = SCRIPT_DEBUG ? '' : '.min';

				// Set custom script ID.
				$script_id = 'gp-project-icon-project-form';

				wp_register_script(
					$script_id,
					GP_PROJECT_ICON_DIR_URL . 'assets/js/' . 'project-form' . $suffix . '.js',
					array(
						'jquery',
					),
					GP_PROJECT_ICON_VERSION,
					false
				);

				gp_enqueue_scripts( $script_id );

			}

		}


		/**
		 * Load things after templates.
		 *
		 * @since 1.0.0
		 *
		 * @param string               $template   The template name.
		 * @param array<string,string> $args       Arguments passed to the template.
		 *
		 * @return void
		 */
		public static function post_template_load( $template, &$args ) {

			// Currently unused.

			if ( $template === 'header' ) {
				echo 'PEDRO';
			}

			// Unset unused variables.
			//unset( $template, $args );
		}


		/**
		 * Register and enqueue style sheet.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public static function register_plugin_styles() {

			wp_enqueue_media();
		}


		/**
		 * Register and enqueue scripts.
		 *
		 * @since 1.0.0
		 *
		 * @param string             $template       GlotPress template name.
		 * @param array<string>|null $args           GlotPress template arguments.
		 * @param array<int, string> $dependencies   Array of script dependencies.
		 *
		 * @return void
		 */
		public static function register_plugin_scripts( $template, &$args, $dependencies = array() ) {

			wp_enqueue_media();

			// Check if SCRIPT_DEBUG is true.
			$suffix = SCRIPT_DEBUG ? '' : '.min';

			// Set custom script ID.
			$script_id = sprintf(
				'gp-project-icon-%s',
				$template
			);

			gp_register_script(
				$script_id,
				GP_PROJECT_ICON_DIR_URL . 'assets/js/' . $template . $suffix . '.js',
				$dependencies,
				GP_PROJECT_ICON_VERSION,
				false
			);

			gp_enqueue_scripts( $script_id );

			wp_set_script_translations(
				$script_id,
				'gp-project-icon'
			);

			wp_localize_script(
				$script_id,
				'gpToolbox',
				array(
					'admin'              => self::current_user_is_glotpress_admin(),                                   // GlotPress Admin with manage options capability.
					'gp_url'             => gp_url(),                                                                  // GlotPress base URL. Defaults to /glotpress/.
					'gp_url_project'     => gp_url_project(),                                                          // GlotPress projects base URL. Defaults to /glotpress/projects/.
					'nonce'              => wp_create_nonce( 'wp_rest' ),                                              // Authenticate in the Rest API.
					'args'               => ! is_null( $args ) ? self::{'template_args_' . $template}( $args ) : null, // Template arguments.
					'supported_statuses' => self::supported_translation_statuses(),                                    // Supported translation statuses.
					'user_locale'        => GP_locales::by_field( 'wp_locale', get_user_locale() ),                    // Current user Locale.
					'user_login'         => wp_get_current_user()->user_login,                                         // Current user login (username).
					/**
					 * Filters wether to color highlight or not the translation stats counts of the translation sets on the project page.
					 *
					 * @since 1.0.0
					 *
					 * @param bool $highlight_counts  True to highlight, false to don't highlight. Defaults to true.
					 */
					'highlight_counts'   => apply_filters( 'gp_project_icon_highlight_counts', $highlight_counts = true ),  // Wether or not to highlight the translation sets table.
				)
			);
		}


		/**
		 * Register and enqueue scripts.
		 *
		 * @since 1.0.0
		 *
		 * @param string             $template       GlotPress template name.
		 * @param array<string>|null $args           GlotPress template arguments.
		 * @param array<int, string> $dependencies   Array of script dependencies.
		 *
		 * @return void
		 */
		public static function register_scripts() {

			wp_enqueue_media();

			// Check if SCRIPT_DEBUG is true.
			$suffix = SCRIPT_DEBUG ? '' : '.min';

			$template = 'project-form';

			// Set custom script ID.
			$script_id = sprintf(
				'gp-project-icon-%s',
				$template
			);

			wp_register_script(
				$script_id,
				GP_PROJECT_ICON_DIR_URL . 'assets/js/' . $template . $suffix . '.js',
				array( 'jquery' ),
				GP_PROJECT_ICON_VERSION,
				false
			);

			gp_enqueue_scripts( $script_id );

		}


		/**
		 * Project template arguments.
		 *
		 * @since 1.0.0
		 *
		 * @param array<string, mixed> $args   GlotPress template arguments.
		 *
		 * @return array<string, mixed>   Array of template arguments.
		 */
		public static function template_args_project( array $args ) {

			$result = array();

			$result['project'] = $args['project'];

			if ( is_array( $args['translation_sets'] ) ) {
				foreach ( $args['translation_sets'] as $translation_set ) {
					$result['translation_sets'][ $translation_set->locale ] = $translation_set;
				}
			}

			// Return Project and Translation Sets.
			return $result;
		}


		/**
		 * Tools template arguments.
		 *
		 * @since 1.0.0
		 *
		 * @param array<string, mixed> $args   GlotPress template arguments.
		 *
		 * @return array<string, mixed>   Array of template arguments.
		 */
		public static function template_args_tools( array $args ) {

			$result = $args;

			return $result;
		}


		/**
		 * Get the translation statuses to manage.
		 * Currently GlotPress project tables only show the 'current', 'fuzzy' and 'waiting' strings.
		 * This enables all the statuses, adding the columns 'old', 'rejected' and 'changesrequested' to the project tables.
		 * The list is filterable by 'gp_project_icon_supported_translation_statuses' below.
		 *
		 * @since 1.0.0
		 *
		 * @return array<string, string>   Translations statuses to enable management.
		 */
		public static function supported_translation_statuses() {

			$glotpress_statuses = array(
				'current'  => esc_html__( 'Current', 'gp-project-icon' ),
				'fuzzy'    => esc_html__( 'Fuzzy', 'gp-project-icon' ),
				'waiting'  => esc_html__( 'Waiting', 'gp-project-icon' ),
				'old'      => esc_html__( 'Old', 'gp-project-icon' ),
				'rejected' => esc_html__( 'Rejected', 'gp-project-icon' ),
				// TODO: Uncomment when the gp-translation-helpers is merged in GlotPress.
				/**
				 * 'changesrequested' => esc_html__( 'Changes requested', 'gp-project-icon' ), // phpcs:ignore
				 */
			);

			$supported_statuses = array_keys( $glotpress_statuses );

			/**
			 * Filter to set the translation statuses to manage with GP Project Icon.
			 *
			 * @since 1.0.0
			 *
			 * @param array $supported_statuses   The array of the supported statuses to enable management, check and cleanup.
			 */
			$filtered_statuses = apply_filters( 'gp_project_icon_supported_translation_statuses', $supported_statuses );

			// Sanitize the filtered statuses.
			$statuses = array();
			foreach ( $filtered_statuses as $filtered_status ) {
				if ( array_key_exists( $filtered_status, $glotpress_statuses ) ) {
					$statuses[ $filtered_status ] = $glotpress_statuses[ $filtered_status ];
				}
			}

			return $statuses;
		}


		/**
		 * Check if the current user is logged in, can manage options and has GlotPress admin previleges.
		 *
		 * @since 1.0.0
		 *
		 * @return bool   Return true or false.
		 */
		public static function current_user_is_glotpress_admin() {

			// Check if user is logged in.
			if ( ! is_user_logged_in() ) {
				return false;
			}

			// Check if user can manage options.
			if ( ! current_user_can( 'manage_options' ) ) {
				return false;
			}

			// Check if user has GlotPress admin previleges.
			if ( ! GP::$permission->current_user_can( 'admin' ) ) {
				return false;
			}

			return true;
		}

	}
}
