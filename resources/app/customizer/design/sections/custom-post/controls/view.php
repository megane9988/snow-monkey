<?php
/**
 * @package snow-monkey
 * @author inc2734
 * @license GPL-2.0+
 * @version 5.3.4
 */

use Inc2734\WP_Customizer_Framework\Framework;
use Framework\Helper;
use Framework\Controller\Controller;

$custom_post_types = Helper::get_custom_post_types();

foreach ( $custom_post_types as $custom_post_type ) {
	Framework::control(
		'select',
		$custom_post_type . '-view',
		[
			'label'       => __( 'View template', 'snow-monkey' ),
			'description' => __( 'Select the view template to use.', 'snow-monkey' ),
			'priority'    => 100,
			'default'     => '',
			'choices'     => [
				''     => __( 'default', 'snow-monkey' ),
				'post' => __( 'The view template of the post', 'snow-monkey' ),
				'page' => __( 'The view template of the page', 'snow-monkey' ),
			],
			'active_callback' => function() {
				return 'content' === Controller::get_view();
			},
		]
	);

	if ( ! is_customize_preview() ) {
		return;
	}

	$panel   = Framework::get_panel( 'design' );
	$section = Framework::get_section( 'design-' . $custom_post_type );
	$control = Framework::get_control( $custom_post_type . '-view' );
	$control->join( $section )->join( $panel );
}
