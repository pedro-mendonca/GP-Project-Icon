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
	<?php esc_html_e( 'Overview of all Translations.', 'gp-project-icon' ); ?>
</p>
<ul class="gptoolbox-description">
	<li><?php esc_html_e( 'Find Translations with obsolete original. (e.g. Related Project originals were updated and set to obsolete)', 'gp-project-icon' ); ?></li>
	<li><?php esc_html_e( 'Find Translations with unknown original. (e.g. Related Original was deleted)', 'gp-project-icon' ); ?></li>
</ul>
<?php

// TODO: One click clean Translations with Onsolete or Unknown originals.
