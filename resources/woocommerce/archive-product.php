<?php
/**
 * @package snow-monkey
 * @author inc2734
 * @license GPL-2.0+
 */

use Inc2734\Mimizuku_Core\App\Controller\Controller;

Controller::layout( get_theme_mod( 'woocommerce-archive-page-layout' ) );
Controller::render( 'woocommerce-archive-product' );
