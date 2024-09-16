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
	'/tools/'           => esc_html__( 'Tools', 'gp-project-icon' ),
	'/tools/originals/' => esc_html__( 'Originals', 'gp-project-icon' ),
);

// Get GlotPress page title.
Toolbox::page_title( $breadcrumbs );

// Get GlotPress breadcrumbs.
Toolbox::page_breadcrumbs( $breadcrumbs );

// Load GlotPress Header template.
gp_tmpl_header();

$args = array(
	'title'       => esc_html__( 'Originals', 'gp-project-icon' ), // Page title.
	'description' => esc_html__( 'Overview of all Originals for each Project.', 'gp-project-icon' ), // Page description.
);

// Load GP-Project-Icon header template.
gp_tmpl_load( 'gptoolbox-header', $args );

?>
<p class="gptoolbox-description">
	<?php echo wp_kses_post( __( 'Each Original has a parent <code>project_id</code>. If there is no parent Project in the database with the same ID, then the Original is orphaned.', 'gp-project-icon' ) ); ?>
	<br>
	<?php
	echo wp_kses_post(
		wp_sprintf(
			/* translators: %l: List of originals statuses. */
			esc_html__( 'The Originals can have one of the following statuses: %l.', 'gp-project-icon' ),
			array(
				'<code>+active</code>',
				'<code>-obsolete</code>',
			)
		)
	);
	?>
</p>

<?php

// Get GlotPress originals.
$gp_originals = array();
foreach ( GP::$original->all() as $original ) {
	$gp_originals[ $original->id ] = $original;
}

// Get GlotPress projects.
$gp_projects = array();
foreach ( GP::$project->all() as $project ) {
	$gp_projects[ $project->id ] = $project;
}

// TODO: Reponse if empty.

// GlotPress core originals statuses.
$gp_original_statuses = array(
	'active'   => array(
		'singular' => esc_html_x( 'Active', 'Singular noun', 'gp-project-icon' ),
		'plural'   => esc_html_x( 'Active', 'Plural noun', 'gp-project-icon' ),
	),
	'obsolete' => array(
		'singular' => esc_html_x( 'Obsolete', 'Singular noun', 'gp-project-icon' ),
		'plural'   => esc_html_x( 'Obsolete', 'Plural noun', 'gp-project-icon' ),
	),
);

// Organize originals by project.
$gp_originals_by_project = array();

// Organize originals by status.
$gp_originals_by_status = array(
	'+active'   => array(), // GlotPress core Original active status.
	'-obsolete' => array(), // GlotPress core Original obsolete status.
);

// Originals of unknown projects.
$orphaned_originals = array();

foreach ( $gp_originals as $original ) {

	// Originals by project.
	$gp_originals_by_project[ $original->project_id ][ $original->status ][ $original->id ] = $original;

	// Originals by status.
	$gp_originals_by_status[ $original->status ][ $original->id ] = $original;

	// Originals of unknown projects.
	if ( ! isset( $gp_projects[ $original->project_id ] ) ) {
		$orphaned_originals[ $original->project_id ][ $original->id ] = $original;
	}
}

// TODO: Reponse if empty.

?>
<section class="gp-project-icon originals">
	<?php

	// Check for Originals.
	if ( empty( $gp_originals_by_project ) ) {
		?>
		<p id="originals-status-filters"><?php esc_html_e( 'No originals found.', 'gp-project-icon' ); ?></p>
		<?php
	} else {
		?>
		<p id="originals-status-filters">
			<?php

			// Originals: All {total} originals. {active} active originals. {obsolete} obsolete originals.
			echo wp_kses_post(
				sprintf(
					/* translators: %s: Links to filter the table. */
					__( 'Originals: %s', 'gp-project-icon' ),
					sprintf(
						'%1$s %2$s %3$s %4$s',
						'<a id="originals-status-all" class="originals-status" href="#originals-status-filters">' . sprintf(
							/* translators: %s: Number of Originals. */
							_n( '%s original.', 'All %s originals.', count( $gp_originals_by_status['+active'] ) + count( $gp_originals_by_status['-obsolete'] ), 'gp-project-icon' ),
							'<strong class="originals-label originals-label-all">' . esc_html( number_format_i18n( count( $gp_originals_by_status['+active'] ) + count( $gp_originals_by_status['-obsolete'] ) ) ) . '</strong>'
						) . '</a>',
						count( $gp_originals_by_status['+active'] ) > 0 ? '<a id="originals-status-active" class="originals-status" href="#originals-status-filters">' . sprintf(
							/* translators: %s: Number of Originals. */
							_n( '%s active original.', '%s active originals.', count( $gp_originals_by_status['+active'] ), 'gp-project-icon' ),
							'<strong class="originals-label originals-label-active">' . esc_html( number_format_i18n( count( $gp_originals_by_status['+active'] ) ) ) . '</strong>'
						) . '</a>' : '',
						count( $gp_originals_by_status['-obsolete'] ) > 0 ? '<a id="originals-status-obsolete" class="originals-status" href="#originals-status-filters">' . sprintf(
							/* translators: %s: Number of Originals. */
							_n( '%s obsolete original.', '%s obsolete originals.', count( $gp_originals_by_status['-obsolete'] ), 'gp-project-icon' ),
							'<strong class="originals-label originals-label-obsolete">' . esc_html( number_format_i18n( count( $gp_originals_by_status['-obsolete'] ) ) ) . '</strong>'
						) . '</a>' : '',
						count( $orphaned_originals ) > 0 ? '<a id="originals-orphaned" class="originals-status" href="#originals-status-filters">' . sprintf(
							/* translators: %s: Number of Projects. */
							_n( 'Originals from %s unknown project.', 'Originals from %s unknown projects.', count( $orphaned_originals ), 'gp-project-icon' ),
							'<strong class="originals-label originals-label-orphaned">' . esc_html( number_format_i18n( count( $orphaned_originals ) ) ) . '</strong>'
						) . '</a>' : ''
					)
				)
			);

			?>
		</p>

		<div class="originals-filter">
			<label for="originals-filter"><?php esc_html_e( 'Filter:', 'gp-project-icon' ); ?> <input id="originals-filter" type="text" placeholder="<?php esc_attr_e( 'Search', 'gp-project-icon' ); ?>" /> </label>
			<button id="originals-filter-clear" class="button" style="margin-bottom: 3px;" title="<?php esc_attr_e( 'Clear search filter.', 'gp-project-icon' ); ?>"><?php esc_html_e( 'Clear', 'gp-project-icon' ); ?></button>
		</div>

		<table id="originals" class="gp-table gp-project-icon tools-originals">
			<thead>
				<tr>
					<th class="gp-column-project"><?php esc_html_e( 'Project', 'gp-project-icon' ); ?></th>
					<th class="gp-column-status active"><?php echo esc_html( $gp_original_statuses['active']['plural'] ); ?></th>
					<th class="gp-column-status obsolete"><?php echo esc_html( $gp_original_statuses['obsolete']['plural'] ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php

				foreach ( $gp_originals_by_project as $project_id => $statuses ) {

					$project = $gp_projects[ $project_id ] ?? false;

					$active_count   = isset( $statuses['+active'] ) ? count( $statuses['+active'] ) : 0;
					$obsolete_count = isset( $statuses['-obsolete'] ) ? count( $statuses['-obsolete'] ) : 0;

					?>
					<tr gptoolboxdata-project="<?php echo esc_attr( strval( $project_id ) ); ?>">
						<?php
						if ( ! $project ) {
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
											esc_html( strval( $project_id ) )
										)
									);
									?>
								</span>
							</td>
							<?php
						} else {
							?>
							<td class="project" data-text="<?php echo esc_attr( $project->name ); ?>">
								<?php
								gp_link_project( $project, esc_html( $project->name ) );
								?>
							</td>
							<?php
						}

						?>
						<td class="stats active" data-text="<?php echo esc_attr( strval( $active_count ) ); ?>">
							<?php
							echo esc_html( number_format_i18n( $active_count ) );
							?>
						</td>
						<td class="stats obsolete" data-text="<?php echo esc_attr( strval( $obsolete_count ) ); ?>">
							<?php
							echo esc_html( number_format_i18n( $obsolete_count ) );
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
