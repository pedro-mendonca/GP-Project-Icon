<?php
/**
 * Template file.
 *
 * @package GP_Project_Icon
 *
 * @since 1.0.0
 */

namespace GP_Project_Icon;

use GP;
use GP_Locales;

// Set the page breadcrumbs.
$breadcrumbs = array(
	'/tools/'                  => esc_html__( 'Tools', 'gp-project-icon' ),
	'/tools/translation-sets/' => esc_html__( 'Translation Sets', 'gp-project-icon' ),
);

// Get GlotPress page title.
Toolbox::page_title( $breadcrumbs );

// Get GlotPress breadcrumbs.
Toolbox::page_breadcrumbs( $breadcrumbs );

// Load GlotPress Header template.
gp_tmpl_header();

$args = array(
	'title'       => esc_html__( 'Translation Sets', 'gp-project-icon' ), // Page title.
	'description' => esc_html__( 'Overview of all Translation Sets.', 'gp-project-icon' ), // Page description.
);

// Load GP-Project-Icon header template.
gp_tmpl_load( 'gptoolbox-header', $args );

?>
<p class="gptoolbox-description">
	<?php
	echo wp_kses_post( __( 'Each Translation Set has a parent <code>project_id</code>. If there is no parent Project in the database with the same ID, then the Translation Set is orphaned.', 'gp-project-icon' ) );
	?>
</p>
<?php

// Get GlotPress translation sets.
$gp_translation_sets = array();
foreach ( GP::$translation_set->all() as $translation_set ) {
	// Don't show Locale Glossary virtual projects with ID '0'.
	if ( $translation_set->project_id === 0 ) {
		continue;
	}
	$gp_translation_sets[ $translation_set->id ] = $translation_set;
}

// Get GlotPress projects.
$gp_projects = array();
foreach ( GP::$project->all() as $project ) {
	$gp_projects[ $project->id ] = $project;
}

$orphaned_translation_sets = array();
foreach ( $gp_translation_sets as $translation_set ) {
	if ( ! isset( $gp_projects[ $translation_set->project_id ] ) ) {
		$orphaned_translation_sets[ $translation_set->project_id ][ $translation_set->id ] = $translation_set;
	}
}

// TODO: Allow delete Translation Sets entries.

?>
<section class="gp-project-icon translation-sets">
	<?php

	// Check for translation sets.
	if ( empty( $gp_translation_sets ) ) {
		?>
		<p id="translation-sets-filters"><?php esc_html_e( 'No translation sets found.', 'gp-project-icon' ); ?></p>
		<?php
	}

	// Check for translation sets and orphaned translations.
	if ( ! empty( $gp_translation_sets ) || ! empty( $orphaned_translations ) ) {

		?>
		<div class="translation-sets-filter">
			<label for="translation-sets-filter"><?php esc_html_e( 'Filter:', 'gp-project-icon' ); ?> <input id="translation-sets-filter" type="text" placeholder="<?php esc_attr_e( 'Search', 'gp-project-icon' ); ?>" /> </label>
			<button id="translation-sets-filter-clear" class="button" style="margin-bottom: 3px;" title="<?php esc_attr_e( 'Clear search filter.', 'gp-project-icon' ); ?>"><?php esc_html_e( 'Clear', 'gp-project-icon' ); ?></button>
		</div>

		<table class="gp-table gp-project-icon tools-translation-sets">
			<thead>
				<tr>
					<th class="gp-column-id"><?php esc_html_e( 'ID', 'gp-project-icon' ); ?></th>
					<th class="gp-column-name"><?php esc_html_e( 'Name', 'gp-project-icon' ); ?></th>
					<th class="gp-column-slug"><?php esc_html_e( 'Slug', 'gp-project-icon' ); ?></th>
					<th class="gp-column-project"><?php esc_html_e( 'Project', 'gp-project-icon' ); ?></th>
					<th class="gp-column-set-locale"><?php esc_html_e( 'Locale', 'gp-project-icon' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php

				foreach ( $gp_translation_sets as $translation_set ) {

					?>
					<tr gptoolboxdata-translation-set="<?php echo esc_attr( strval( $translation_set->id ) ); ?>">
						<td class="id"><?php echo esc_html( strval( $translation_set->id ) ); ?></td>
						<td class="name"><?php echo esc_html( strval( $translation_set->name ) ); ?></td>
						<td class="slug"><?php echo esc_html( strval( $translation_set->slug ) ); ?></td>
						<?php

						// Get translation set project.
						$project = $gp_projects[ $translation_set->project_id ] ?? false;

						// Check if project is known. Double check for GP_Project object.
						if ( ! $project ) {
							// Unknown project.

							?>
							<td class="project unknown" data-text="">
								<span class="unknown">
									<?php
									printf(
										/* translators: Known identifier data. */
										esc_html__( 'Unknown project (%s)', 'gp-project-icon' ),
										sprintf(
											/* translators: %d ID number. */
											esc_html__( 'ID #%d', 'gp-project-icon' ),
											esc_html( $translation_set->project_id )
										)
									);
									?>
								</span>
							</td>
							<?php
						} else {
							// Known project.

							?>
							<td class="project" data-text="<?php echo esc_attr( $project->name ); ?>">
								<?php
								gp_link_project( $project, esc_html( $project->name ) );
								?>
							</td>
							<?php
						}
						?>
						<td class="set-locale">
							<?php
							// Get translation set locale.
							$translation_set_locale = GP_Locales::by_slug( $translation_set->locale );

							gp_link( gp_url_join( gp_url( '/languages' ), $translation_set_locale->slug ), $translation_set_locale->slug );
							?>
						</td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
		<?php
	}
	?>
</section>
<?php

// Load GlotPress Footer template.
gp_tmpl_footer();
