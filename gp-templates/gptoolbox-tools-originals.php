<?php
/**
 * Template file.
 *
 * @package GP_Project_Icon
 *
 * @since 1.0.0
 */

namespace GP_Project_Icon;

?>
<p class="gptoolbox-description">
	<?php esc_html_e( 'Overview of all active and obsolete Originals for each Project.', 'gp-project-icon' ); ?>
</p>
<ul class="gptoolbox-description">
	<li><?php esc_html_e( 'Find Originals for unknown Projects. (e.g. Related Project was deleted)', 'gp-project-icon' ); ?></li>
	<li><?php esc_html_e( 'Find obsolete Originals.', 'gp-project-icon' ); ?></li>
</ul>

<?php
// TODO: One click clean Originals for unknown projects, and delete obsoletes.
