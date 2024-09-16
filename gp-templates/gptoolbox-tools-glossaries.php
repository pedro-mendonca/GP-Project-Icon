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
	<?php esc_html_e( 'Overview of all Global and Project Glossaries.', 'gp-project-icon' ); ?>
</p>
<ul class="gptoolbox-description">
	<li><?php esc_html_e( 'Find Glossaries of unknown Translation Sets or Projects. (e.g. Related Translation Set or Project were deleted)', 'gp-project-icon' ); ?></li>
	<li><?php esc_html_e( 'Find Glossary Entries of unknown Glossaries. (e.g. Related Glossary was deleted)', 'gp-project-icon' ); ?></li>
</ul>

<?php
// TODO: One click clean Glossaries for unknown projects, and delete obsoletes.
