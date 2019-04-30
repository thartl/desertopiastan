<?php
/**
 * Description
 *
 * @package     ParkdaleWire\DTS_Core
 * @since       1.0.0
 * @author      thartl
 * @link        https://parkdalewire.com
 * @license     GNU General Public License 2.0+
 */

namespace ParkdaleWire\DTS_Core;


add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_assets' );
/**
 * Enqueue the plugin assets.
 *
 * @return void
 * @since 1.0.0
 *
 */
function enqueue_assets() {

//	wp_enqueue_style( 'dashicons' );

	wp_enqueue_style(
		'micromodal-styles',
		DTS_CORE_FUNCTIONALITY_URL . 'assets/css/micromodal.css',
		array(),
		'1.0.0'
	);

	wp_enqueue_script(
		'micromodal-script',
		DTS_CORE_FUNCTIONALITY_URL . 'assets/js/micromodal.min.js',
		array(),
		'1.0.0',
		true
	);

	wp_enqueue_script(
		'dts-core-main-script',
		DTS_CORE_FUNCTIONALITY_URL . 'assets/js/global.js',
		array( 'micromodal-script' ),
		'1.0.0',
		true
	);

	wp_enqueue_script(
		'pw-jump',
		DTS_CORE_FUNCTIONALITY_URL . 'assets/js/pw-jump.js',
		array( 'jquery' ),
		'1.0.0',
		true
	);

	wp_enqueue_script(
		'scroll-to-anchor',
		DTS_CORE_FUNCTIONALITY_URL . 'assets/js/scroll-to-anchor.js',
		array( 'jquery', 'pw-jump' ),
		'1.0.0',
		true
	);

}


// Include post types and taxonomies
require_once DTS_CORE_FUNCTIONALITY_DIR . 'inc/cpt-creature.php';


// Print modal before `</body>`
add_action( 'wp_footer', function() {

	include DTS_CORE_FUNCTIONALITY_DIR . 'views/modal.php';

}, 100 );


