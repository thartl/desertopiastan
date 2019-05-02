<?php
/**
 * PW Base.
 *
 * This page template enqueues isotope.js
 *
 * Template Name: Isotope page
 *
 * @package PW Base
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://www.studiopress.com/
 */


add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_assets' );
/**
 * Enqueue isotope.js
 *
 * @return void
 * @since 1.0.0
 *
 */
function enqueue_assets() {

	wp_enqueue_script(
		'scroll-to-anchor',
		DTS_CORE_FUNCTIONALITY_URL . 'assets/js/scroll-to-anchor.js',
		array( 'jquery', 'pw-jump' ),
		'1.0.0',
		true
	);

}


genesis();
