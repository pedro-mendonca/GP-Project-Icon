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
		 * @param string              $template   The template name.
		 * @param array<string,mixed> $args       Arguments passed to the template.
		 *
		 * @return void
		 */
		public static function pre_template_load( $template, $args ) {

			if ( $template === 'projects' ) {

				// TODO: Check against the actual version of GP that includes the required hook.
				if ( self::check_gp_version_is_compatible( '>=', '4.0.2' ) ) {

					// Add icon to the project link.
					add_filter( 'gp_projects_template_project_items', array( self::class, 'project_row_items' ), 10, 2 );

				} else {
					// Fallback to modifying the template DOM without filter.
					$projects = $args['projects'];

					if ( is_array( $projects ) ) {

						$args['project_icon_urls'] = self::get_project_icons_urls( $projects );
						$args['projects_list_id']  = 'projects';

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
			}

			if ( $template === 'project' && isset( $args['project'] ) ) {

				$project = $args['project'];

				if ( $project instanceof GP_Project ) {

					// Add project icon on Project header, below notices.
					add_action(
						'gp_after_notices',
						function () use ( $project ) {

							// Get existent project icon.
							$project_icon = self::get_project_icon( $project );

							if ( $project_icon ) {

								// Project has icon, show icon image.
								$project_icon_img = wp_get_attachment_image_src(
									$project_icon,
									array( 128, 128 )
								);

								if ( $project_icon_img ) {

									$project_icon_url = $project_icon_img[0];
									?>
									<div class='project-header-icon-wrapper'>
										<img class='project-icon' src='<?php echo esc_url( $project_icon_url ); ?>'>
									</div>
									<?php

								}
							}
						}
					);

					// TODO: Check against the actual version of GP that includes the required hook.
					if ( self::check_gp_version_is_compatible( '>=', '4.0.2' ) ) {
						// Add icon to the project link.
						add_filter( 'gp_project_template_subproject_items', array( self::class, 'project_row_items' ), 10, 2 );

					} else {
						// Fallback to modifying the template DOM without filter.
						$sub_projects = $args['sub_projects'];

						if ( is_array( $sub_projects ) ) {

							$args['project_icon_urls'] = self::get_project_icons_urls( $sub_projects );
							$args['projects_list_id']  = 'sub-projects';

							// Register and enqueue GlotPress project template scripts.
							add_action(
								'wp_enqueue_scripts',
								function () use ( $args ) {
									self::register_plugin_scripts(
										'projects', // Override template name to reuse the same JavaScript from projects.js.
										$args,
										array(
											'jquery',
										)
									);
								}
							);

						}
					}
				}
			}

			if ( $template === 'project-edit' || $template === 'project-new' ) {

				// Load media templates and footer scripts on gp_footer, as wp_footer doesn't exist.
				add_action( 'gp_footer', 'wp_print_media_templates', 10 );
				add_action( 'gp_footer', 'wp_print_footer_scripts', 20 );

				// Load the Media uploader styles and scripts.
				wp_enqueue_media();

				// Register and enqueue GlotPress project template scripts.
				add_action(
					'wp_enqueue_scripts',
					function () use ( $args ) {
						self::register_plugin_scripts(
							'project-edit', // Override template name so that both 'project-edit' and 'project-new' use the same JavaScript.
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
		 * Update project icon.
		 *
		 * @since 1.0.0
		 *
		 * @param array      $project_row_items   The array of project items to render on the projects list.
		 * @param GP_Project $project             GP_Project object.
		 *
		 * @return array   The modified array of project items to render on the projects list.
		 */
		public static function project_row_items( $project_row_items, $project ) {

			$project_icon_url = self::get_project_icon_url( $project );

			$project_row_items['link-name'] = gp_link_project_get(
				$project,
				'<span class="icon"><img src="' . $project_icon_url . '"></span><span class="name">' . esc_html( $project->name ) . '</span>'
			);

			return $project_row_items;
		}


		/**
		 * Register and enqueue scripts.
		 *
		 * @since 1.0.0
		 *
		 * @param string                   $template       GlotPress template name.
		 * @param array<string,mixed>|null $args           GlotPress template arguments.
		 * @param array<int, string>       $dependencies   Array of script dependencies.
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
					'gp_url'         => gp_url(),                          // GlotPress base URL. Defaults to /glotpress/.
					'gp_url_project' => gp_url_project(),                  // GlotPress projects base URL. Defaults to /glotpress/projects/.
					'args'           => ! is_null( $args ) ? $args : null, // Template arguments.
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
			<dt class="project-icon">
				<label for="project[icon]"><?php esc_html_e( 'Icon', 'gp-project-icon' ); ?></label>
			</dt>
			<dd class="project-icon">
				<div class='icon-preview-wrapper'>
				<?php

				$project_icon_url = '';

				$button_delete_visibile = false;

				// Project Icon preview.
				if ( $project_icon && $project_icon !== '' ) {

					$project_icon_img = wp_get_attachment_image_src(
						$project_icon,
						array( 32, 32 )
					);

					if ( $project_icon_img ) {

						$project_icon_url = $project_icon_img[0];

						$button_delete_visibile = true;
					}
				}

				$button_delete_visibility = $button_delete_visibile ? 'flex' : 'none';

				$button_text_color = $project_icon_url ? 'transparent' : 'var( --gp-color-btn-text )';

				?>

					<input id="project-icon-button-select" type="button" value="<?php esc_html_e( 'Select Icon', 'gp-project-icon' ); ?>" class="button" style="background-image: url(<?php echo esc_url( $project_icon_url ); ?>); color: <?php echo esc_attr( $button_text_color ); ?>;">

					<button id="project-icon-button-clear" class="button is-primary" style="display: <?php echo esc_attr( $button_delete_visibility ); ?>;">
						<span class="dashicons dashicons-no"></span>
						<span class="screen-reader-text"><?php esc_html_e( 'Clear', 'gp-project-icon' ); ?></span>
					</button>

				</div>

				<input type='hidden' name="project[icon]" id='icon_attachment_id' value='<?php echo esc_attr( (string) $project_icon ); ?>'>
				<?php
				// Add nonce check.
				wp_nonce_field( 'project_icon_select', 'project_icon_nonce' );
				?>

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
		 * @return string|false    URL string, or false if not found.
		 */
		public static function get_project_icon_url( $project ) {

			$project_icon = self::get_project_icon( $project );

			if ( ! $project_icon ) {
				return false;
			}

			$project_icon_img = wp_get_attachment_image_src(
				$project_icon,
				array( 32, 32 )
			);

			if ( ! $project_icon_img ) {
				return false;
			}

			$project_icon_url = $project_icon_img[0];

			return $project_icon_url;
		}


		/**
		 * Get project attachment ID of the icon.
		 *
		 * @since 1.0.0
		 *
		 * @param GP_Project $project   GlotPress Project.
		 *
		 * @return int|false    ID of the attachment, or false if not found.
		 */
		public static function get_project_icon( $project ) {

			$project_icon = gp_get_meta( 'project', $project->id, 'project_icon' );

			if ( ! is_numeric( $project_icon ) ) {
				return false;
			}

			return (int) $project_icon;
		}


		/**
		 * Update project icon.
		 *
		 * @since 1.0.0
		 *
		 * @param GP_Project $project   GlotPress Project.
		 *
		 * @return void
		 */
		public static function update_project_icon( $project ) {

			if ( ! isset( $_POST['project']['icon'] ) ) {
				return;
			}

			// Check nonce.
			if ( ! isset( $_POST['project_icon_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['project_icon_nonce'] ), 'project_icon_select' ) ) {

				// Invalid nonce, possible CSRF attack.
				wp_die( 'Sorry, your nonce did not verify.', 'gp-project-icon' );
			}

			$project_icon = sanitize_key( $_POST['project']['icon'] );

			// Add/update project icon metadata.
			if ( $project_icon ) {
				gp_update_meta( $project->id, 'project_icon', $project_icon, 'project' );
				return;
			}

			// If no project icon is set, delete matching meta from DB.
			gp_delete_meta( $project->id, 'project_icon', $project_icon, 'project' );
		}


		/**
		 * Delete project icon.
		 *
		 * @since 1.0.0
		 *
		 * @param GP_Project $project   GlotPress Project.
		 *
		 * @return void
		 */
		public static function delete_project_icon( $project ) {

			gp_delete_meta( $project->id, 'project_icon', null, 'project' );
		}


		/**
		 * Check if the required GlotPress version is installed.
		 *
		 * @since 1.0.0
		 *
		 * @param string $operator   Compare operator.
		 * @param string $version    GlotPress required version.
		 *
		 * @return bool
		 */
		public static function check_gp_version_is_compatible( $operator, $version ) {

			// Check if current GlotPress version is compatible.
			return version_compare( GP_VERSION, $version, $operator );
		}
	}
}
