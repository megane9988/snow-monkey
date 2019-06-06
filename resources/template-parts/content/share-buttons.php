<?php
/**
 * @package snow-monkey
 * @author inc2734
 * @license GPL-2.0+
 * @version 6.0.0
 */

use Framework\Helper;

$buttons = Helper::get_var( $_buttons, explode( ',', get_option( 'mwt-share-buttons-buttons' ) ) );

if ( ! $buttons ) {
	return;
}
?>

<div class="wp-share-buttons wp-share-buttons--<?php echo esc_attr( get_option( 'mwt-share-buttons-type' ) ); ?>">
	<ul class="wp-share-buttons__list">
		<?php foreach ( $buttons as $button ) : ?>
			<li class="wp-share-buttons__item">
				<?php
				echo do_shortcode(
					sprintf(
						'[wp_share_buttons_%1$s type="%2$s" post_id="%3$d"]',
						esc_attr( $button ),
						esc_attr( get_option( 'mwt-share-buttons-type' ) ),
						get_the_ID()
					)
				);
				?>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
