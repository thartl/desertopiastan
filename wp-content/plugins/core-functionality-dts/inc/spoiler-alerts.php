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




