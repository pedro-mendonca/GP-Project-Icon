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

// Set the page breadcrumbs.
$breadcrumbs = array(
	'/tools/'              => esc_html__( 'Tools', 'gp-project-icon' ),
	'/tools/translations/' => esc_html__( 'Translations', 'gp-project-icon' ),
);

// Get GlotPress page title.
Toolbox::page_title( $breadcrumbs );

// Get GlotPress breadcrumbs.
Toolbox::page_breadcrumbs( $breadcrumbs );

// Load GlotPress Header template.
gp_tmpl_header();

$args = array(
	'title'       => esc_html__( 'Translations', 'gp-project-icon' ), // Page title.
	'description' => esc_html__( 'Overview of all Translations, for each Translation Set.', 'gp-project-icon' ), // Page description.
);

// Load GP-Project-Icon header template.
gp_tmpl_load( 'gptoolbox-header', $args );

?>
<p class="gptoolbox-description">
	<?php
	$statuses_list = array();
	foreach ( Toolbox::supported_translation_statuses() as $key => $translation_status ) {
		$statuses_list[ $key ] = '<span class="translation-status ' . esc_attr( $key ) . '">' . esc_html( $translation_status ) . '</span>';
	}
	echo wp_kses_post(
		wp_sprintf(
			/* translators: %l: List of translation statuses. */
			esc_html__( 'The Translations can have one of the following statuses: %l.', 'gp-project-icon' ),
			$statuses_list
		)
	);
	?>
	<br>
	<?php
	echo wp_kses_post( __( 'Each Translation has a parent <code>translation_set_id</code>. If there is no parent Translation Set in the database with the same ID, then the Translation is orphaned.', 'gp-project-icon' ) );
	?>
	<br>
	<?php
	echo wp_kses_post( __( 'Each Translation has a parent <code>original_id</code>. If there is no parent Original in the database with the same ID, then the Translation is orphaned.', 'gp-project-icon' ) );
	?>
	<br>
	<?php
	echo wp_kses_post( __( 'The original of each translation can be <code>Active</code>, <code>Obsolete</code> or <code>Unknown</code>:', 'gp-project-icon' ) );
	?>
	<ul>
		<li>
			<?php
			echo wp_kses_post(
				sprintf(
					/* translators: 1: List item title. 2: List item description. */
					esc_html__( '%1$s - %2$s', 'gp-project-icon' ),
					'<strong>' . esc_html__( 'Translations (Active Originals)', 'gp-project-icon' ) . '</strong>',
					esc_html__( 'Translations related to the originals currently used in the translation project.', 'gp-project-icon' )
				)
			);
			?>
		</li>
		<li>
			<?php
			echo wp_kses_post(
				sprintf(
					/* translators: 1: List item title. 2: List item description. */
					esc_html__( '%1$s - %2$s', 'gp-project-icon' ),
					'<strong>' . esc_html__( 'Translations (Obsolete Originals)', 'gp-project-icon' ) . '</strong>',
					esc_html__( 'Translations related to originals that aren\'t currently used in the translation project, but remain in the database for possible later use.', 'gp-project-icon' )
				)
			);
			?>
		</li>
		<li>
			<?php
			echo wp_kses_post(
				sprintf(
					/* translators: 1: List item title. 2: List item description. */
					esc_html__( '%1$s - %2$s', 'gp-project-icon' ),
					'<strong>' . esc_html__( 'Translations (Unknown Originals)', 'gp-project-icon' ) . '</strong>',
					esc_html__( 'Translations related to originals that no longer exist in the database, making them orphaned.', 'gp-project-icon' )
				)
			);
			?>
		</li>
	</ul>

</p>
<?php

// Get GlotPress translations.
$gp_translations = array();
foreach ( GP::$translation->all() as $translation ) {
	$gp_translations[ $translation->id ] = $translation;
}

// Get GlotPress translation sets.
$gp_translation_sets = array();
foreach ( GP::$translation_set->all() as $translation_set ) {
	$gp_translation_sets[ $translation_set->id ] = $translation_set;
}

// Get GlotPress projects.
$gp_projects = array();
foreach ( GP::$project->all() as $project ) {
	$gp_projects[ $project->id ] = $project;
}

// Get GlotPress originals.
$gp_originals = array();
foreach ( GP::$original->all() as $original ) {
	$gp_originals[ $original->id ] = $original;
}

// Translations by Translation Set.
$translations_by_translation_set = array();

// Set general translation counts.
$translations_with_active_original_count    = 0;
$translations_with_obsolete_original_count  = 0;
$translations_with_unknown_original_count   = 0;
$translations_from_unknown_translation_sets = 0;

foreach ( $gp_translations as $translation_id => $translation ) {
	$translations_by_translation_set[ $translation->translation_set_id ][ $translation_id ] = $translation;

	if ( isset( $gp_originals[ $translation->original_id ] ) ) {
		if ( $gp_originals[ $translation->original_id ]->status === '+active' ) {
			++$translations_with_active_original_count;
		} elseif ( $gp_originals[ $translation->original_id ]->status === '-obsolete' ) {
			++$translations_with_obsolete_original_count;
		}
	} else {
		++$translations_with_unknown_original_count;
	}
}

foreach ( $translations_by_translation_set as $translation_set_id => $translations ) {
	if ( ! isset( $gp_translation_sets[ $translation_set_id ] ) ) {
		$translations_from_unknown_translation_sets += count( $translations_by_translation_set[ $translation_set_id ] );
	}
}

// TODO: Allow delete Translations.

?>
<section class="gp-project-icon translations">
	<?php

	// Check for translations.
	if ( empty( $gp_translations ) ) {
		?>
		<p id="translations-filters"><?php esc_html_e( 'No translations found.', 'gp-project-icon' ); ?></p>
		<?php
	} else {

		?>
		<p id="translations-filters">
			<?php

			// Translations: All {total} translations. {active} translations with active originals. {obsolete} translations with obsolete originals. {unknown} translations with unknown originals.
			echo wp_kses_post(
				sprintf(
					/* translators: %s: Links to filter the table. */
					__( 'Translations: %s', 'gp-project-icon' ),
					sprintf(
						'%1$s %2$s %3$s %4$s %5$s',
						'<a id="translations-all" class="translations" href="#translations-filters">' . sprintf(
							/* translators: %s: Number of Translations. */
							_n( '%s translation.', 'All %s translations.', count( $gp_translations ), 'gp-project-icon' ),
							'<strong class="translations-label translations-label-all">' . esc_html( number_format_i18n( count( $gp_translations ) ) ) . '</strong>'
						) . '</a>',
						$translations_with_active_original_count > 0 ? '<a id="translations-active-original" class="translations" href="#translations-filters">' . sprintf(
							/* translators: %s: Number of Translations. */
							_n( '%s translation witn active original.', '%s translations with active originals.', $translations_with_active_original_count, 'gp-project-icon' ),
							'<strong class="translations-label translations-label-active">' . esc_html( number_format_i18n( $translations_with_active_original_count ) ) . '</strong>'
						) . '</a>' : '',
						$translations_with_obsolete_original_count > 0 ? '<a id="translations-obsolete-original" class="translations" href="#translations-filters">' . sprintf(
							/* translators: %s: Number of Translations. */
							_n( '%s translation with obsolete original.', '%s translations with obsolete originals.', $translations_with_obsolete_original_count, 'gp-project-icon' ),
							'<strong class="translations-label translations-label-obsolete">' . esc_html( number_format_i18n( $translations_with_obsolete_original_count ) ) . '</strong>'
						) . '</a>' : '',
						$translations_with_unknown_original_count > 0 ? '<a id="translations-unknown-original" class="translations" href="#translations-filters">' . sprintf(
							/* translators: %s: Number of Translations. */
							_n( '%s translation with unknown original.', '%s translations with unknown originals.', $translations_with_unknown_original_count, 'gp-project-icon' ),
							'<strong class="translations-label translations-label-unknown">' . esc_html( number_format_i18n( $translations_with_unknown_original_count ) ) . '</strong>'
						) . '</a>' : '',
						$translations_from_unknown_translation_sets > 0 ? '<a id="translations-unknown-translation-set" class="translations" href="#translations-filters">' . sprintf(
							/* translators: %s: Number of Translations. */
							_n( '%s translation from unknown translation set.', '%s translations from unknown translation sets.', $translations_from_unknown_translation_sets, 'gp-project-icon' ),
							'<strong class="translations-label translations-label-unknown">' . esc_html( number_format_i18n( $translations_from_unknown_translation_sets ) ) . '</strong>'
						) . '</a>' : ''
					)
				)
			);

			?>
		</p>

		<div class="translations-filter">
			<label for="translations-filter"><?php esc_html_e( 'Filter:', 'gp-project-icon' ); ?> <input id="translations-filter" type="text" placeholder="<?php esc_attr_e( 'Search', 'gp-project-icon' ); ?>" /> </label>
			<button id="translations-filter-clear" class="button" style="margin-bottom: 3px;" title="<?php esc_attr_e( 'Clear search filter.', 'gp-project-icon' ); ?>"><?php esc_html_e( 'Clear', 'gp-project-icon' ); ?></button>
		</div>

		<table id="translations" class="gp-table gp-project-icon tools-translations">
			<thead>
				<tr>
					<th class="gp-column-translation-set"><?php esc_html_e( 'Translation Set', 'gp-project-icon' ); ?></th>
					<th class="gp-column-project"><?php esc_html_e( 'Project', 'gp-project-icon' ); ?></th>
					<th class="gp-column-originals-active"><?php esc_html_e( 'Active Originals', 'gp-project-icon' ); ?></th>
					<th class="gp-column-originals-obsolete"><?php esc_html_e( 'Obsolete Originals', 'gp-project-icon' ); ?></th>
					<th class="gp-column-originals-unknown"><?php esc_html_e( 'Unknown Originals', 'gp-project-icon' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php

				foreach ( $translations_by_translation_set as $translation_set_id => $translations ) {

					$translation_set = $gp_translation_sets[ $translation_set_id ] ?? false;

					if ( $translation_set && $translation_set->project_id === 0 ) {
						// Don't show Locale Glossary virtual projects with ID '0'.
						continue;
					}

					?>
					<tr gptoolboxdata-translation-set="<?php echo esc_attr( strval( $translation_set_id ) ); ?>">

						<?php
						if ( $translation_set ) {
							?>
							<td class="translation-set"><?php echo esc_html( strval( $translation_set->name ) ); ?></td>

							<?php
							// Get translation set project.
							$project = $gp_projects[ $translation_set->project_id ] ?? false;

							// Check if project is known. Double check for GP_Project object.
							if ( ! $project ) {
								// Unknown project.

								?>
								<td class="project" data-text="">
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
						} else {
							?>
							<td class="translation-set unknown" data-text="">
								<span class="unknown">
									<?php
									printf(
										/* translators: %d ID number. */
										esc_html__( 'Unknown (ID #%d)', 'gp-project-icon' ),
										esc_html( strval( $translation_set_id ) )
									);
									?>
								</span>
							</td>
							<td class="project" data-text="">
								<span class="unknown">
									<?php
									esc_html_e( 'Unknown', 'gp-project-icon' );
									?>
								</span>
							</td>
							<?php
						}

						// Translations by original.
						$originals_active_count   = 0;
						$originals_obsolete_count = 0;
						$originals_unknown_count  = 0;

						$translations = $translations_by_translation_set[ $translation_set_id ];
						foreach ( $translations as $translation ) {
							if ( isset( $gp_originals[ $translation->original_id ] ) ) {
								if ( $gp_originals[ $translation->original_id ]->status === '+active' ) {
									++$originals_active_count;
								} elseif ( $gp_originals[ $translation->original_id ]->status === '-obsolete' ) {
									++$originals_obsolete_count;
								}
							} else {
								++$originals_unknown_count;
							}
						}

						?>
						<td class="stats originals-active" data-text="<?php echo esc_attr( strval( $originals_active_count ) ); ?>">
							<?php

							if ( $translation_set && isset( $gp_projects[ $translation_set->project_id ] ) ) {

								// Filter by all supported statuses.
								$status_filter = implode( '_or_', array_keys( $statuses_list ) );

								$url = add_query_arg(
									'filters[status]',
									$status_filter,
									gp_url_project_locale(
										$gp_projects[ $translation_set->project_id ],
										$translation_set->locale,
										$translation_set->slug
									)
								);
								echo wp_kses_post(
									gp_link_get(
										$url,
										esc_html( number_format_i18n( $originals_active_count ) )
									)
								);
							} else {
								echo esc_html( number_format_i18n( $originals_active_count ) );
							}
							?>
						</td>

						<td class="stats originals-obsolete" data-text="<?php echo esc_attr( strval( $originals_obsolete_count ) ); ?>">
							<?php
							echo esc_html( number_format_i18n( $originals_obsolete_count ) );
							?>
						</td>

						<td class="stats originals-unknown" data-text="<?php echo esc_attr( strval( $originals_unknown_count ) ); ?>">
							<?php
							echo esc_html( number_format_i18n( $originals_unknown_count ) );
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

gp_tmpl_footer();
