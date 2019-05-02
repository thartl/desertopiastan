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


add_filter( 'genesis_markup_entry_open', __NAMESPACE__ . '\add_anchors_to_blog_listings' );
/**
 * Adds html ids to blog page article listings.
 *
 * @param $entry    string  Opening markup
 *
 * @return mixed    string  (maybe) filtered markup
 * @since   1.0.0
 *
 */
function add_anchors_to_blog_listings( $entry ) {

	global $post;

	if ( ! $entry || is_single() || $post->post_type != 'post' ) {

		return $entry;

	}

	$article_with_id = '<article id="post-' . $post->post_name . '" ';

	return str_replace( '<article ', $article_with_id, $entry );

}


/**
 * Get read-more tag OR return false
 *
 * @return  string|bool     Read-more tag in lower-string, or false
 *
 */
function maybe_get_readmore_tag() {

	global $post;

	$has_readmore_tag = preg_match( '/<!--more(.*?)?-->/', $post->post_content, $matches );

	if ( $has_readmore_tag ) {

		return strtolower( $matches[0] );

	}

	return false;

}


/**
 * Description.
 *
 * @return bool
 *
 */
function has_spoilers() {

	if ( maybe_get_readmore_tag() ) {

		return 'spoiler-->' == substr( maybe_get_readmore_tag(), - 10 );

	}

}


/**
 * Are we on the Guide Page?
 *
 * @return bool
 *
 */
function is_Guide_Page() {

	global $wp_query;

	return 'guide' == $wp_query->query_vars['pagename'];

}




add_filter( 'the_content_more_link', __NAMESPACE__ . '\th_customize_read_more', 10, 2 );
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

//	global $wp_query;

	// Detect `spoiler` keyword in read-more link (site-wide)
	if ( 'spoiler' == substr( strtolower( $more_link_text ), - 7 ) ) {

		$maybe_alert_class = ' spoiler-alert';
		$more_link_text    = substr( $more_link_text, 0, - 7 );

		// All read-more links on the Guide page are spoiler alerts
	} elseif ( is_Guide_Page() ) {

		$maybe_alert_class = ' spoiler-alert';

	} else {

		$maybe_alert_class = '';

	}

	return ' <a href="' . get_permalink() . '" class="more-link' . $maybe_alert_class . '">' . $more_link_text . '</a>';

}


add_filter( 'genesis_post_title_output', __NAMESPACE__ . '\adjust_heading_maybe_spoiler_maybe_unlink', 10, 3 );
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
	if ( is_single() ) {
		return $output;
	}

	// Unexpected HTML structure: Bail now
	if ( strpos( $title, '<a class="' ) !== 0 ) {
		return $output;
	}


	// Add spoiler alert
	if ( has_spoilers() || ( is_Guide_Page() && maybe_get_readmore_tag() ) ) {

		// Add class to trigger "Spoiler alert" modal on click
		$title = substr_replace( $title, 'spoiler-alert ', 10, 0 );

	}

	// Unlink heading on Guide page, when there is no additional content to go to
	if ( is_Guide_Page() && ! maybe_get_readmore_tag() ) {

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


