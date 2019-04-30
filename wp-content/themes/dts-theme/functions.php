<?php
/**
 * PW Base.
 *
 * This file adds functions to the PW Base Theme.
 *
 * @package PW Base
 * @author  Parkdale Wire
 * @license GPL-2.0+
 * @link    http://www.parkdalewire.com/
 */

// Start the engine.
include_once( get_template_directory() . '/lib/init.php' );

// Setup Theme.
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

// Set Localization (do not remove).
add_action( 'after_setup_theme', 'pw_base_localization_setup' );
function pw_base_localization_setup() {
	load_child_theme_textdomain( 'pw-base', get_stylesheet_directory() . '/languages' );
}

// Add the helper functions.
include_once( get_stylesheet_directory() . '/lib/helper-functions.php' );

// Add Image upload and Color select to WordPress Theme Customizer.
require_once( get_stylesheet_directory() . '/lib/customize.php' );

// Include Customizer CSS.
include_once( get_stylesheet_directory() . '/lib/output.php' );

// Add WooCommerce support.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php' );

// Add the required WooCommerce styles and Customizer CSS.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php' );

// Add the Genesis Connect WooCommerce notice.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php' );


// Enqueue style.css and minify, or enqueue style.min.css if available.  Cache-busting and enqueued at higher priority.
include_once( get_stylesheet_directory() . '/lib/main-style-min.php' );


// Child theme (do not remove).
define( 'CHILD_THEME_NAME', 'DTS Theme' );
define( 'CHILD_THEME_URL', 'http://www.parkdalewire.com/' );
define( 'CHILD_THEME_VERSION', '1.0.0' );

// Enqueue Scripts and Styles.
add_action( 'wp_enqueue_scripts', 'pw_base_enqueue_scripts_styles' );
function pw_base_enqueue_scripts_styles() {


	wp_enqueue_style( 'pw-base-fonts', '//fonts.googleapis.com/css?family=Nunito:300,400,700|Comfortaa:400,700', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'dashicons' );

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_script( 'pw-base-responsive-menu', get_stylesheet_directory_uri() . "/js/responsive-menus{$suffix}.js", array( 'jquery' ), CHILD_THEME_VERSION, true );
	wp_localize_script(
		'pw-base-responsive-menu',
		'genesis_responsive_menu',
		pw_base_responsive_menu_settings()
	);
}

// Define our responsive menu settings.
function pw_base_responsive_menu_settings() {

	$settings = array(
		'mainMenu'          => __( 'Menu', 'pw-base' ),
		'menuIconClass'     => 'dashicons-before dashicons-menu',
		'subMenu'           => __( 'Submenu', 'pw-base' ),
		'subMenuIconsClass' => 'dashicons-before dashicons-arrow-down-alt2',
		'menuClasses'       => array(
			'combine' => array(
				'.nav-primary',
				'.nav-header',
			),
			'others'  => array(),
		),
	);

	return $settings;

}

// Add HTML5 markup structure.
add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

// Add Accessibility support.
add_theme_support( 'genesis-accessibility', array(
	'404-page',
	'drop-down-menu',
	'headings',
	'rems',
	'search-form',
	'skip-links'
) );

// Add viewport meta tag for mobile browsers.
add_theme_support( 'genesis-responsive-viewport' );

// Add support for custom header.
add_theme_support( 'custom-header', array(
	'width'           => 600,
	'height'          => 160,
	'header-selector' => '.site-title a',
	'header-text'     => false,
	'flex-height'     => true,
) );

// Add support for custom background.
add_theme_support( 'custom-background' );

// Add support for after entry widget.
add_theme_support( 'genesis-after-entry-widget-area' );

// Add support for 3-column footer widgets.
add_theme_support( 'genesis-footer-widgets', 3 );

// Add Image Sizes.
add_image_size( 'featured-image', 720, 400, true );

// Rename primary and secondary navigation menus.
add_theme_support( 'genesis-menus', array(
	'primary'   => __( 'After Header Menu', 'pw-base' ),
	'secondary' => __( 'Footer Menu', 'pw-base' )
) );

// Reposition the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 5 );

// Reduce the secondary navigation menu to one level depth.
add_filter( 'wp_nav_menu_args', 'pw_base_secondary_menu_args' );
function pw_base_secondary_menu_args( $args ) {

	if ( 'secondary' != $args['theme_location'] ) {
		return $args;
	}

	$args['depth'] = 1;

	return $args;

}

// Modify size of the Gravatar in the author box.
add_filter( 'genesis_author_box_gravatar_size', 'pw_base_author_box_gravatar' );
function pw_base_author_box_gravatar( $size ) {
	return 90;
}

// Modify size of the Gravatar in the entry comments.
add_filter( 'genesis_comment_list_args', 'pw_base_comments_gravatar' );
function pw_base_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;

	return $args;

}


// Remove custom Genesis custom header style
//remove_action( 'wp_head', 'genesis_custom_header_style' );
/**********************************
 *
 * Replace Header Site Title with Inline Logo
 *
 * @author AlphaBlossom / Tony Eppright, Neil Gee
 * @link   http://www.alphablossom.com/a-better-wordpress-genesis-responsive-logo-header/
 * @link   https://wpbeaches.com/adding-in-a-responsive-html-logoimage-header-via-the-customizer-for-genesis/
 *
 * @edited by Sridhar Katakam
 * @link   https://sridharkatakam.com/
 *
 ************************************/
add_filter( 'genesis_seo_title', 'custom_header_inline_logo', 10, 3 );
function custom_header_inline_logo( $title, $inside, $wrap ) {
	if ( get_header_image() ) {
		$logo = '<img  src="' . get_header_image() . '" width="' . esc_attr( get_custom_header()->width ) . '" height="' . esc_attr( get_custom_header()->height ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . ' Homepage">';
	} else {
		$logo = get_bloginfo( 'name' );
	}
	$inside = sprintf( '<a href="%s">%s<span class="screen-reader-text">%s</span></a>', trailingslashit( home_url() ), $logo, get_bloginfo( 'name' ) );
	// Determine which wrapping tags to use
	$wrap = genesis_is_root_page() && 'title' === genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : 'p';
	// A little fallback, in case an SEO plugin is active
	$wrap = genesis_is_root_page() && ! genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : $wrap;
	// And finally, $wrap in h1 if HTML5 & semantic headings enabled
	$wrap = genesis_html5() && genesis_get_seo_option( 'semantic_headings' ) ? 'h1' : $wrap;

	return sprintf( '<%1$s %2$s>%3$s</%1$s>', $wrap, genesis_attr( 'site-title' ), $inside );
}

// Hide tagline
// add_filter( 'genesis_attr_site-description', 'abte_add_site_description_class' );
/**
 * Add class for screen readers to site description.
 *
 * Unhook this if you'd like to show the site description.
 *
 * @param array $attributes Existing HTML attributes for site description element.
 *
 * @return string Amended HTML attributes for site description element.
 * @since 1.0.0
 *
 */
function abte_add_site_description_class( $attributes ) {
	$attributes['class'] .= ' screen-reader-text';

	return $attributes;
}

//this function removes the "this topic contains..." and "this forum contains..."  text
function no_description( $retstr ) {
	$retstr = "";

	return $retstr;
}

add_filter( 'bbp_get_single_topic_description', 'no_description' );
add_filter( 'bbp_get_single_forum_description', 'no_description' );

//This function changes the text wherever it is quoted
// function change_translate_text( $translated_text ) {
// 	if ( $translated_text == 'Your account has the ability to post unrestricted HTML content.' ) {
// 	$translated_text = '';
// 	}
// 	if ( $translated_text == 'Oh bother! No topics were found here!' ) {
// 	$translated_text = '';
// 	}
// 	return $translated_text;
// }
// add_filter( 'gettext', 'change_translate_text', 20 );


// Remove from forum breadcrumbs: any link that contains 'bbp-breadcrumb-root'
//add_filter( 'bbp_breadcrumbs', 'th_forum_breadcrumbs_root' );
function th_forum_breadcrumbs_root( $crumbs ) {

	foreach ( $crumbs as $key => $crumb ) {

		if ( strpos( $crumb, 'bbp-breadcrumb-root' ) !== false ) {
			unset( $crumbs[ $key ] );
		}

	}

	return $crumbs;

}

/*
Plugin Name: bbPress - Custom Breadcrumbs
Plugin URI: https://gist.github.com/ntwb/7781901
Description: bbPress - Custom Breadcrumbs
Version: 0.1
Author: Stephen Edgar - Netweb
Author URI: http://netweb.com.au
*/

//add_filter('bbp_before_get_breadcrumb_parse_args', 'ntwb_bbpress_custom_breadcrumb' );

function ntwb_bbpress_custom_breadcrumb() {

	// Forum root
	$args['include_root'] = false;

	//$args['root_text']    = 'text';

	return $args;
}

// Increase number of forums

function bbp_increase_forum_per_page( $args = array() ) {
	$args['posts_per_page'] = get_option( '_bbp_forums_per_page', 100 );

	return $args;
}

add_filter( 'bbp_before_has_forums_parse_args', 'bbp_increase_forum_per_page' );

/*
Plugin Name: bbPress - Custom Remove 'Protected:' and 'Private:' title prefixes
Plugin URI: https://gist.github.com/ntwb/8662354
Description: bbPress - Custom Remove 'Protected:' and 'Private:' title prefixes
Version: 0.1
Author: Stephen Edgar - Netweb
Author URI: http://netweb.com.au
*/
add_filter( 'protected_title_format', 'ntwb_remove_protected_title' );
function ntwb_remove_protected_title( $title ) {
	return '%s';
}

add_filter( 'private_title_format', 'ntwb_remove_private_title' );
function ntwb_remove_private_title( $title ) {
	return '%s';
}

// remove post info.
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

//* Add new featured image size
add_image_size( 'sm-thumb', 100, 100, true );
add_image_size( 'tiny-thumb', 70, 70, true );

// Register the three useful image sizes for use in Add Media modal
add_filter( 'image_size_names_choose', 'amy_custom_sizes' );
function amy_custom_sizes( $sizes ) {
	return array_merge( $sizes, array(
		'sm-thumb'   => __( 'Smaller Thumbnail' ),
		'tiny-thumb' => __( 'Tinier Thumbnail' ),
	) );
}


add_filter( 'genesis_markup_entry_open', 'th_add_anchors_to_blog' );
/**
 * Adds html ids to blog page article listings.
 *
 * @param $entry    string  Opening markup
 *
 * @return mixed    string  (maybe) filtered markup
 * @since   1.0.0
 *
 */
function th_add_anchors_to_blog( $entry ) {

	global $post;

	if ( ! $entry || is_single() || $post->post_type != 'post' ) {

		return $entry;

	}

	$article_with_id = '<article id="post-' . $post->post_name . '" ';

	return str_replace( '<article ', $article_with_id, $entry );

}


add_filter( 'the_content_more_link', 'th_customize_read_more', 10, 2 );
/**
 * Customize the read-more link: anchor to "balance" of content not used in this version.
 * Also: detect `spoiler` -> remove `spoiler` from link text + add `spoiler-alert` class.
 * Or: attach `spoiler-alert` class if on the Guide page
 *
 * @param string $read_more_link
 * @param string $more_link_text
 *
 * @return string
 * @since   1.0.0
 *
 */
function th_customize_read_more( $read_more_link, $more_link_text ) {

	global $wp_query;

	// Detect `spoiler` keyword in read-more link (site-wide)
	if ( 'spoiler' == substr( strtolower( $more_link_text ), - 7 ) ) {

		$maybe_alert_class = ' spoiler-alert';
		$more_link_text    = substr( $more_link_text, 0, - 7 );

		// All read-more links on the Guide page are spoiler alerts
	} elseif ( 'guide' == $wp_query->query_vars['pagename'] ) {

		$maybe_alert_class = ' spoiler-alert';

	} else {

		$maybe_alert_class = $maybe_alert_data = '';

	}

	return ' <a href="' . get_permalink() . '" class="more-link' . $maybe_alert_class . '">' . $more_link_text . '</a>';

}


add_action( 'display_posts_shortcode_output', 'be_dps_template_part', 10, 2 );
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
function be_dps_template_part( $output, $original_atts ) {

	// Return early if our "layout" attribute is not specified
	if ( empty( $original_atts['layout'] ) ) {
		return $output;
	}

	ob_start();
	get_template_part( 'partials/display-posts-shortcode/layout', $original_atts['layout'] );
	$new_output = ob_get_clean();

	if ( ! empty( $new_output ) ) {
		$output = $new_output;
	}

	return $output;
}


add_filter( 'genesis_post_title_output', 'adjust_heading_maybe_spoiler_maybe_unlink', 10, 3 );
/**
 * Modifies titles blog and archive pages:
 *
 * Attaches "Spoiler" warning to a listing's heading: if we're on the Guide page and listing contain a read-more tag, or
 * site-wide if a read-more tag ends with `spoiler`
 * Otherwise, removes <a> tags from the post title.
 *
 * @param $output
 * @param $wrap
 * @param $title
 *
 * @return string|null
 * @since   1.0.0
 *
 */
function adjust_heading_maybe_spoiler_maybe_unlink( $output, $wrap, $title ) {

	global $wp_query, $post;

	// Bail on single pages
	if( is_single() ) {
		return $output;
	}

	// Unexpected HTML structure: Bail now
	if ( strpos( $title, '<a class="' ) !== 0 ) {
		return $output;
	}


	$has_readmore_link = preg_match( '/<!--more(.*?)?-->/', $post->post_content, $matches );

	$contains_spoilers = '';
	if ( isset( $matches[0] ) ) {
		$contains_spoilers = substr( strtolower( $matches[0] ), - 10 ) == 'spoiler-->';
	}

	$on_Guide_page = $wp_query->query_vars['pagename'] == 'guide';


	// Add spoiler alert
	if ( $contains_spoilers || ( $on_Guide_page && $has_readmore_link ) ) {

		// Add class to trigger "Spoiler alert" modal
		$title = substr_replace( $title, 'spoiler-alert ', 10, 0 );

	}

	// Unlink heading on Guide page, when there is no additional content to go to
	if ( $on_Guide_page && ! $has_readmore_link ) {

		// Remove `a` tags from listings that have NO read-more content
		if ( ! preg_match( '/<!--more(.*?)?-->/', $post->post_content ) ) {

			$title = wp_strip_all_tags( $title );

		}

	}


	// Build the output.
	$output = genesis_markup(
		array(
			'open'    => "<{$wrap} %s>",
			'close'   => "</{$wrap}>",
			'content' => $title,
			'context' => 'entry-title',
			'params'  => array(
				'wrap' => $wrap,
			),
			'echo'    => false,
		)
	);

	return $output;

}


