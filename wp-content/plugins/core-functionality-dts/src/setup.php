<?php
/**
 * Set up constants, include files, and enqueue resources.
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

//	wp_enqueue_script(
//		'pw-jump',
//		DTS_CORE_FUNCTIONALITY_URL . 'assets/js/pw-jump.js',
//		array( 'jquery' ),
//		'1.0.0',
//		true
//	);

//	wp_enqueue_script(
//		'scroll-to-anchor',
//		DTS_CORE_FUNCTIONALITY_URL . 'assets/js/scroll-to-anchor.js',
//		array( 'jquery', 'pw-jump' ),
//		'1.0.0',
//		true
//	);

	wp_enqueue_script(
		'imagesloaded-js',
		DTS_CORE_FUNCTIONALITY_URL . 'assets/js/imagesloaded.pkgd.min.js',
		array( 'jquery' ),
		'1.0.0',
		true
	);

	wp_enqueue_script(
		'isotope-js',
		DTS_CORE_FUNCTIONALITY_URL . 'assets/js/isotope.pkgd.min.js',
		array( 'jquery', 'imagesloaded-js' ),
		'1.0.0',
		true
	);

}


add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\load_admin_styles' );
/**
 * Enqueue admin styles.
 *
 * @return void
 * @since   1.0.0
 *
 */
function load_admin_styles() {

	wp_enqueue_style(
		'dts-admin-style',
		DTS_CORE_FUNCTIONALITY_URL . 'assets/css/admin-style.css',
		array(),
		'1.0.0'
	);

}


// Include post types and taxonomies
require_once DTS_CORE_FUNCTIONALITY_DIR . 'inc/cpt-creature.php';
require_once DTS_CORE_FUNCTIONALITY_DIR . 'inc/cpt-table.php';

// Helpers, mostly for inside loops, mostly for Species CPT
require_once DTS_CORE_FUNCTIONALITY_DIR . 'inc/loop-helpers.php';

// Spoiler Alert helpers + Guide page post navigation helpers
require_once DTS_CORE_FUNCTIONALITY_DIR . 'inc/spoiler-alerts.php';

// CPT Tables: helpers and layout
require_once DTS_CORE_FUNCTIONALITY_DIR . 'inc/tables.php';

// Print modal before `</body>`
add_action( 'wp_footer', function () {

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


add_action( 'display_posts_shortcode_output', __NAMESPACE__ . '\use_templates_for_display_posts_shortcode', 10, 2 );
/**
 * Template Parts with Display Posts Shortcode
 *
 * @param string $output        , current output of post
 * @param array  $original_atts , original attributes passed to shortcode
 *
 * @return string $output
 * @see    https://www.billerickson.net/template-parts-with-display-posts-shortcode
 *
 * @author Bill Erickson
 */
function use_templates_for_display_posts_shortcode( $output, $original_atts ) {

	// Return early if our "layout" attribute is not specified
	if ( empty( $original_atts['layout'] ) ) {
		return $output;
	}

	ob_start();
	include DTS_CORE_FUNCTIONALITY_DIR . 'views/display-posts-shortcode/layout-' . $original_atts['layout'] . '.php';
	$new_output = ob_get_clean();

	if ( ! empty( $new_output ) ) {
		$output = $new_output;
	}

	return $output;
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

	$output = '<p class="signup-button"><a class="button" href="' . esc_html( home_url( '/register' ) ) . '">Sign Up</a></p>';
	$output .= do_shortcode( '[wps_login]Log in[/wps_login]' );

	return $output;

}


add_shortcode( 'species-grid', __NAMESPACE__ . '\species_grid' );
/**
 * Display Species Grid with Filters and Sorting
 */
function species_grid() {

	ob_start();

	display_filter_sort_button_groups();

	display_species_grid();

	return ob_get_clean();

}


add_shortcode( 'species-icon-list', __NAMESPACE__ . '\species_icon_list' );
/**
 * Display a list of habitat icons, maybe filtered by habitat type
 */
function species_icon_list( $atts ) {

	$a = shortcode_atts( array(
		'type' => 'all',
	), $atts );

	ob_start();

	display_habitat_icons( $a['type'] );

	return ob_get_clean();

}


//add_action( 'pre_get_posts', __NAMESPACE__ . '\exclude_posts_from_blog_page' );
/**
 * Remove some posts from the blog page.
 *
 * Deactivated. Instead hidden in css on desktop screens only.
 *
 * @param $query
 *
 * @return void
 */
function exclude_posts_from_blog_page( $query ) {

	if ( $query->is_home() && $query->is_main_query() ) {

		$query->set( 'post__not_in', array( 1660 ) );
	}
}



// Add class to paragraphs with 30+ characters of text outside of tags
add_filter( 'the_content', __NAMESPACE__ . '\mark_paragraphs_with_text_content' );
function mark_paragraphs_with_text_content( $content ) {

	// TODO: filter out paragraphs - maybe just ones that do (not) start with another tag (not strong??...)

//	d( $content );

	return $content;

}

