<?php
/**
 * @package snow-monkey
 * @author inc2734
 * @license GPL-2.0+
 * @version 11.0.0
 */

use Framework\Helper;
?>

<?php do_action( 'snow_monkey_before_archive_entry_content' ); ?>

<div class="c-entry__content p-entry-content">
	<?php do_action( 'snow_monkey_prepend_archive_entry_content' ); ?>

	<p>
		<?php esc_html_e( 'Please enter a search keyword.', 'snow-monkey' ); ?>
	</p>

	<?php Helper::get_template_part( 'template-parts/common/search-form', 'no-match' ); ?>

	<?php do_action( 'snow_monkey_append_archive_entry_content' ); ?>
</div>

<?php do_action( 'snow_monkey_after_archive_entry_content' ); ?>