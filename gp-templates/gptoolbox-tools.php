<?php
/**
 * Template file.
 *
 * @package GP_Project_Icon
 *
 * @since 1.0.0
 */

namespace GP_Project_Icon;

// Set the page breadcrumbs.
$breadcrumbs = array(
	'/tools/' => esc_html__( 'Tools', 'gp-project-icon' ),
);

// Get GlotPress page title.
Toolbox::page_title( $breadcrumbs );

// Get GlotPress breadcrumbs.
Toolbox::page_breadcrumbs( $breadcrumbs );

// Load GlotPress Header template.
gp_tmpl_header();

$args = array(
	'title'       => esc_html__( 'Tools', 'gp-project-icon' ), // Page title.
	'description' => esc_html__( 'Set of tools to manage the object types and relations in the GlotPress database.', 'gp-project-icon' ), // Page description.
);

// Load GP-Project-Icon header template.
gp_tmpl_load( 'gptoolbox-header', $args );

$tools_pages = Toolbox::tools_pages();

// Load Tools sections.
foreach ( $tools_pages as $tools_page ) {
	// Load Tools section navigation template.
	if ( isset( $tools_page['tools_section'] ) ) {
		?>
		<section class="gp-project-icon">
			<h3>
				<a class="gp-project-icon-tool-link" href="<?php echo esc_url( gp_url( $tools_page['url'] ) ); ?>">
					<?php echo esc_html( $tools_page['title'] ); ?>
				</a>
			</h3>
			<?php
			gp_tmpl_load( $tools_page['tools_section'] );
			?>
		</section>
		<?php
	}
}

// Load GlotPress Footer template.
gp_tmpl_footer();
