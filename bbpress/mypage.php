<?php
/**
 * @package snow-monkey
 * @author inc2734
 * @license GPL-2.0+
 * @version 15.7.0
 */

use Framework\Controller\Controller;

Controller::layout( get_theme_mod( 'bbpress-archive-layout' ) );
Controller::render( 'bbpress-archive' );
