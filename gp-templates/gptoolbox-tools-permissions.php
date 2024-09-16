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
	<?php esc_html_e( 'Overview of all Administrators and Validators for each Project and Translation Set.', 'gp-project-icon' ); ?>
</p>
<ul class="gptoolbox-description">
	<li><?php esc_html_e( 'Find and delete obsolete Permissions for unknown users, Projects or Translation Sets. (e.g. Related user, Project or Translation Set were deleted)', 'gp-project-icon' ); ?></li>
	<li><?php esc_html_e( 'Find and delete Permission duplicates.', 'gp-project-icon' ); ?></li>
	<li><?php esc_html_e( 'Find and delete Permissions of unknown types.', 'gp-project-icon' ); ?></li>
</ul>

<?php
// TODO: One click clean Permissions for unknown users/projects/translation sets, and delete duplicates.
