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


// Filter login URL for "Genesis login modal box" plugin
// Fix for mangled login URLs on blog and forum pages
add_filter( 'wpstudio_add_login_filter', __NAMESPACE__ . '\set_login_modal_url_to_current_page' );
function set_login_modal_url_to_current_page( $output ) {

	global $wp;

	$current_url = home_url( add_query_arg( array(), $wp->request ) );

	$replacement = '<li class="menu-item login"><a href="' . $current_url . '/#login" title="Login">Login</a></li>';
	
	return $replacement;

}


add_shortcode( 'login_or_user_info', __NAMESPACE__ . '\login_or_user_info' );
/**
 * Outputs either logged-in user information OR login and sign-up form
 */
function login_or_user_info() {

	global $current_user;

	$current_user_name = $current_user->display_name ?: '';

	$logout_link = '<a href="' . wp_logout_url( home_url( '/forums/' ) ) . '">Logout</a>';

	if ( $current_user_name ) {

		return '<p>You are logged in as <span>' . $current_user_name . '</span>.&nbsp;&nbsp;' . $logout_link . '?</p>';

	}

	$output = '<p class="signup-button"><a class="button" href="http://desertopiastan.local/register">Sign Up</a></p>';
	$output .= do_shortcode( '[wps_login]Log in[/wps_login]' );

	return $output;

}


