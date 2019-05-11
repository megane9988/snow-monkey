<?php
/**
 * @package snow-monkey
 * @author inc2734
 * @license GPL-2.0+
 */

use Framework\Helper;

if ( ! Helper::is_active_sidebar( 'archive-sidebar-widget-area' ) ) {
	return;
}
?>

<div class="l-sidebar-widget-area"
	data-is-slim-widget-area="true"
	data-is-content-widget-area="false"
	>

	<?php dynamic_sidebar( 'archive-sidebar-widget-area' ); ?>
</div>
