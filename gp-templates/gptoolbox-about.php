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
	'/tools/'       => esc_html__( 'Tools', 'gp-project-icon' ),
	'/tools/about/' => esc_html__( 'About', 'gp-project-icon' ),
);

// Get GlotPress page title.
Toolbox::page_title( $breadcrumbs );

// Get GlotPress breadcrumbs.
Toolbox::page_breadcrumbs( $breadcrumbs );

// Load GlotPress Header template.
gp_tmpl_header();

$args = array(
	'title'       => esc_html__( 'About GP Project Icon', 'gp-project-icon' ), // Page title.
	'description' => esc_html__( 'This GlotPress plugin allows you to add icons to your projects.', 'gp-project-icon' ), // Page description.
);

// Load GP-Project-Icon header template.
gp_tmpl_load( 'gptoolbox-header', $args );

?>
<section class="gp-project-icon support">
	<h3>
		<?php esc_html_e( 'Support', 'gp-project-icon' ); ?>
		</h3>
	<p>
		<?php
		echo wp_kses_post(
			sprintf(
				/* translators: 1: Link opening <a> tag. 2: Link closing </a> tag. */
				esc_html__( 'For Support, please use the %1$sSupport Forum%2$s on WordPress.org.', 'gp-project-icon' ),
				'<a href="https://wordpress.org/support/plugin/gp-project-icon/" target="_blank">',
				'</a>'
			)
		);
		?>
	</p>
</section>

<section class="gp-project-icon issues-requests">
	<h3>
		<?php esc_html_e( 'Feature requests and bug reports', 'gp-project-icon' ); ?>
	</h3>
	<p>
		<?php
		echo wp_kses_post(
			sprintf(
				/* translators: 1: Link opening <a> tag. 2: Link closing </a> tag. */
				esc_html__( 'You are welcome to report any issues or add feature suggestions on the %1$sGitHub repository%2$s.', 'gp-project-icon' ),
				'<a href="https://github.com/pedro-mendonca/GP-Project-Icon" target="_blank">',
				'</a>'
			)
		);
		?>
	</p>
</section>

<section class="gp-project-icon sponsor">
	<h3>
		<?php esc_html_e( 'Sponsor', 'gp-project-icon' ); ?>
		</h3>
	<p>
		<?php
		echo wp_kses_post(
			sprintf(
				/* translators: 1: Link opening <a> tag. 2: Link closing </a> tag. */
				esc_html__( 'Do you like %1$s? Support its development by becoming a %2$sSponsor%3$s!', 'gp-project-icon' ),
				'<a href="https://github.com/pedro-mendonca/GP-Project-Icon" target="_blank">' . esc_html_x( 'GP Project Icon', 'Plugin name', 'gp-project-icon' ) . '</a>',
				'<a href="https://github.com/sponsors/pedro-mendonca" target="_blank">',
				'</a>'
			)
		);
		?>
	</p>
</section>
<?php

// Load GlotPress Footer template.
gp_tmpl_footer();
