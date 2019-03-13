<?php
/**
 * @package snow-monkey
 * @author inc2734
 * @license GPL-2.0+
 */

use Inc2734\WP_Customizer_Framework\Framework;
use Framework\Helper;

Framework::control(
	'checkbox',
	'mwt-display-related-posts',
	[
		'transport' => 'postMessage',
		'label'     => __( 'Display related posts in posts', 'snow-monkey' ),
		'priority'  => 130,
		'type'      => 'option',
		'default'   => true,
	]
);

if ( ! is_customize_preview() ) {
	return;
}

$panel   = Framework::get_panel( 'design' );
$section = Framework::get_section( 'design-post' );
$control = Framework::get_control( 'mwt-display-related-posts' );
$control->join( $section )->join( $panel );
$control->partial(
	[
		'selector'            => '.p-related-posts',
		'container_inclusive' => true,
		'render_callback'     => function() {
			if ( get_option( 'mwt-display-related-posts' ) ) {
				Helper::get_template_part( 'template-parts/content/related-posts' );
			}
		},
	]
);
