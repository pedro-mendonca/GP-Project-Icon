<?php
/**
 * Class file for the Project_Icon.
 *
 * @package GP_Project_Icon
 *
 * @since 1.0.0
 */

namespace GP_Project_Icon;

use GP_Project;


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( __NAMESPACE__ . '\Project_Icon' ) ) {

	/**
	 * Class Project_Icon.
	 */
	class Project_Icon {


		/**
		 * Registers actions.
		 *
		 * @return void
		 */
		public function __construct() {

			// Load things before templates.
			add_action( 'gp_pre_tmpl_load', array( self::class, 'pre_template_load' ), 10, 2 );

			// Register and enqueue plugin style sheet.
			add_action( 'wp_enqueue_scripts', array( self::class, 'register_plugin_styles' ) );

			// Add Project Icon field to the form.
			add_action( 'gp_after_project_form_fields', array( self::class, 'project_form_icon' ) );

			// Add project icon on project created.
			add_action( 'gp_project_created', array( self::class, 'update_project_icon' ) );

			// Update project icon on project updated.
			add_action( 'gp_project_saved', array( self::class, 'update_project_icon' ) );

			// Delete project icon on project deleted.
			add_action( 'gp_project_deleted', array( self::class, 'delete_project_icon' ) );

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
		public static function pre_template_load( $template, $args ) {

			if ( $template === 'project' && isset( $args['project'] ) ) {

				$project = $args['project'];

				// Add project icon on Project header, below notices.
				add_action( 'gp_after_notices', function () use ( $project ) {

					// Get existent project icon.
					$project_icon = self::get_project_icon( $project );
					//var_dump( $project_icon );
					if ( $project_icon ) {
						// Project has icon, show icon image.
						?>
						<div class='image-preview-wrapper'>
							<img id='image-preview' src='<?php echo wp_get_attachment_url( $project_icon ); ?>' height='128' width='128' style="object-fit: cover;">
						</div>

						<?php

					} else {
						// Project has no icon, show icon placeholder.

					}
				});
			}

			if ( $template === 'project-edit' ) {

				// Load media templates and footer scripts on gp_footer, as wp_footer doesn't exist.
				add_action( 'gp_footer', 'wp_print_media_templates', 10 );
				add_action( 'gp_footer', 'wp_print_footer_scripts', 20 );

				// Load the Media uploader styles and scripts.
				wp_enqueue_media();

				// Register and enqueue GlotPress project template scripts.
				add_action(
					'wp_enqueue_scripts',
					function () use ( $template, $args ) {
						self::register_plugin_scripts(
							$template,
							$args,
							array(
								'jquery',
							)
						);
					}
				);

			}


			if ( $template === 'projects' ) {

				$projects = $args['projects'];

				$args['project_icon_urls'] = self::get_project_icons_urls( $projects );

				// Register and enqueue GlotPress project template scripts.
				add_action(
					'wp_enqueue_scripts',
					function () use ( $template, $args ) {
						self::register_plugin_scripts(
							$template,
							$args,
							array(
								'jquery',
							)
						);
					}
				);
			}

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
		public static function register_plugin_scripts( $template, $args, $dependencies = array() ) {

			// Check if SCRIPT_DEBUG is true.
			$suffix = SCRIPT_DEBUG ? '' : '.min';

			// Set custom script ID.
			$script_id = sprintf(
				'gp-project-icon-%s',
				$template
			);

			wp_register_script(
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
				'gpProjectIcon',
				array(
					'gp_url'             => gp_url(),                          // GlotPress base URL. Defaults to /glotpress/.
					'gp_url_project'     => gp_url_project(),                  // GlotPress projects base URL. Defaults to /glotpress/projects/.
					'args'               => ! is_null( $args ) ? $args : null, // Template arguments.
				)
			);
		}


		/**
		 * Register and enqueue style sheet.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public static function register_plugin_styles() {

			// Remove inline styles added by wp_enqueue_media to the frontend.
			wp_dequeue_style( 'global-styles' );
			wp_dequeue_style( 'admin-bar' );

			// Check if SCRIPT_DEBUG is true.
			$suffix = SCRIPT_DEBUG ? '' : '.min';

			wp_register_style(
				'gp-project-icon',
				GP_PROJECT_ICON_DIR_URL . 'assets/css/style' . $suffix . '.css',
				array(
					'buttons',
				),
				GP_PROJECT_ICON_VERSION
			);

			gp_enqueue_styles(
				array(
					'gp-project-icon',
					'dashicons',
				)
			);
		}


		/**
		 * Add Project Icon field and preview to the Edit Form.
		 *
		 * @since 1.0.0
		 *
		 * @param GP_Project $project   GlotPress Project.
		 *
		 * @return void
		 */
		public static function project_form_icon( $project ) {

			// Get existent project icon.
			$project_icon = self::get_project_icon( $project );


			// Project has icon, show icon image.
			?>
			<style media="screen">
				div.image-preview-wrapper {
					position: relative;
					display: inline-block;
				}
				#frontend-image {
					max-height: 128px;
					width: 128;

					max-height: 128px;
					width: 128px;
					display: block;
					max-width: 100%;

					color: var( --gp-color-fg-default );

					border: 1px solid var( --gp-color-input-border );
					border-radius: 2px;
					box-shadow: none;
					background-color: var( --gp-color-canvas-default );
					background-color: var(--gp-color-primary-50);
					outline: 0;
					object-fit: cover;
				}
				#frontend-button-clear {
					position: absolute;
					top: -12px;
					right: -12px;
					border: none;
					border-radius: 50%;
					width: 24px;
					height: 24px;
					font-size: 18px;
					text-align: center;
					line-height: 22px;
					cursor: pointer;
					padding: 0;
					min-height: unset;
					justify-content: center;
					align-items: center;
				}
				#frontend-button-clear span.dashicons.dashicons-no {
					vertical-align: bottom;
					//font-size: 18px;
					//line-height: 1em;

				}
				#frontend-button {
					display: block;
					z-index: 1;
					/*
					position: absolute;
					left: 50%;
					top: 50%;
					transform: translateX(-50%) translateY(-50%);
					height: 128px;
					width: 128px;
					*/
				}
			</style>
			<dt>
				<label for="project[icon]"><?php esc_html_e( 'Icon', 'gp-project-icon' ); ?></label>
			</dt>
			<dd>
				<div class='image-preview-wrapper'>
				<?php

				$image_source = '';
				$button_delete_visibile = false;

				//var_dump( $project_icon );

				// Project Icon preview.
				if ( $project_icon && $project_icon !== '' ) {

					$image_source = wp_get_attachment_url( $project_icon );
					$button_delete_visibile = true;

				}

				$button_delete_visibility = $button_delete_visibile ? 'flex' : 'none';
				//var_dump( $button_delete_visibility );

				?>
					<img id='frontend-image' src='<?php echo esc_attr( $image_source ); ?>' width='128' height='128'>
					<button id="frontend-button-clear" class="button is-primary" style="display: <?php echo esc_attr( $button_delete_visibility ); ?>;">
						<span class="dashicons dashicons-no"></span>
						<span class="screen-reader-text"><?php esc_html_e( 'Clear', 'gp-project-icon' ); ?></span>
					</button>

				</div>
				<input id="frontend-button" type="button" value="<?php esc_html_e( 'Select', 'gp-project-icon' ); ?>" class="button">


				<?php
				/*
				<input id="frontend-button-clear" type="button" value="<?php esc_html_e( 'Clear', 'gp-project-icon' ); ?>" class="button" style="position: relative; z-index: 1;">
				*/
				?>

				<input type='hidden' name="project[icon]" id='image_attachment_id' value=''>

			</dd>
			<?php
		}


		/**
		 * Get the Icon URLs of the projects.
		 *
		 * @since 1.0.0
		 *
		 * @param array $projects   Array of GP_Project objects.
		 *
		 * @return array            Array of the Icons of every project.
		 */
		public static function get_project_icons_urls( $projects = array() ) {

			$project_icons_urls = array();

			foreach ( $projects as $project ) {
				// TODO: Check which is unique: slug or path.
				$project_icons_urls[ $project->path ] = self::get_project_icon_url( $project );
			}

			return $project_icons_urls;
		}


		/**
		 * Get project icon URL.
		 *
		 * @since 1.0.0
		 *
		 * @param GP_Project $project   GlotPress Project.
		 *
		 * @return mixed|false    Metadata or false.
		 */
		public static function get_project_icon_url( $project ) {

			return wp_get_attachment_url( self::get_project_icon( $project ) );

		}


		/**
		 * Get project attachment ID of the icon.
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
			//var_dump( $_POST );
			//wp_die();

			if ( $project_icon ) {
				return gp_update_meta( $project->id, 'project_icon', $project_icon, 'project' );
			}

			return gp_delete_meta( $project->id, 'project_icon', $project_icon, 'project' );
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
