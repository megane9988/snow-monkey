<?php
/**
 * @package snow-monkey
 * @author inc2734
 * @license GPL-2.0+
 * @version 14.0.0
 */

use Inc2734\WP_Customizer_Framework\Framework;
use Framework\Helper;

$terms = Helper::get_terms( 'post_tag' );

foreach ( $terms as $_term ) {
	Framework::control(
		'image',
		$_term->taxonomy . '-' . $_term->term_id . '-header-image',
		[
			'label'           => __( 'Featured Image', 'snow-monkey' ),
			'description'     => __( 'This setting takes priority over featured image setting of posts page settings', 'snow-monkey' ),
			'priority'        => 110,
			'active_callback' => function() {
				return 'none' !== get_theme_mod( 'archive-eyecatch' );
			},
		]
	);
}

if ( ! is_customize_preview() ) {
	return;
}

$panel = Framework::get_panel( 'design' );

foreach ( $terms as $_term ) {
	$section = Framework::get_section( 'design-' . $_term->taxonomy . '-' . $_term->term_id );
	$control = Framework::get_control( $_term->taxonomy . '-' . $_term->term_id . '-header-image' );
	$control->join( $section )->join( $panel );
}
