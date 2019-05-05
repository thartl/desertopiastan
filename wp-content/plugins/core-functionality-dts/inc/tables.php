<?php
/**
 * Helpers and layout for the Table CPT
 *
 * @package     ParkdaleWire\DTS_Core
 * @since       1.0.0
 * @author      thartl
 * @link        https://parkdalewire.com
 * @license     GNU General Public License 2.0+
 */

namespace ParkdaleWire\DTS_Core;


add_action( 'save_post_table', __NAMESPACE__ . '\assign_id_to_new_table', 10, 3 );
/**
 * If Table post doesn't have an ID number, assign it.
 *
 * @param int  $post_id The post ID.
 * @param post $post    The post object.
 * @param bool $update  Whether this is an existing post being updated or not.
 */
function assign_id_to_new_table( $post_id, $post, $update ) {

	$table_id = (int) get_post_meta( $post_id, 'table_id', true );

	// Bail if a valid table ID exists
	if ( $table_id > 0 ) {
		return;
	}

	// Get the last / highest existing table ID
	$args = array(
		'post_type'      => 'table',
		'posts_per_page' => - 1,
		'orderby'        => 'ID',
		'order'          => 'DESC'
	);

	$all_tables = get_posts( $args );

	// Get the latest table's table_id
	$last_table_post_ID = isset( $all_tables[0] ) ? abs( (int) $all_tables[0]->ID ) : 0;
	$last_table_ID      = (int) get_post_meta( $last_table_post_ID, 'table_id', true );

	wp_reset_postdata();


	// Get highest known / recorded table ID
	$highest_known_ID = abs( (int) get_option( 'pw_highest_table_id' ) );

	$new_ID = max( $highest_known_ID, $last_table_ID );
	$new_ID ++;

	// Set this table ID + update the highest known table ID
	update_post_meta( $post_id, 'table_id', $new_ID );
	update_option( 'pw_highest_table_id', $new_ID, false );

}


add_shortcode( 'tablestan', __NAMESPACE__ . '\tabelstan' );
/**
 * Display table created by "Advanced Custom Fields: Table Field" plugin
 */
function tabelstan( $atts ) {

	$a = shortcode_atts( array(
		'id' => '',
	), $atts );

	if ( ! $a['id'] ) {
		return;
	}

	$table_id = abs( (int) $a['id'] );

	ob_start();

	include DTS_CORE_FUNCTIONALITY_DIR . 'views/tablestan-layout.php';

	return ob_get_clean();

}


add_shortcode( 'ImageSet', __NAMESPACE__ . '\image_set' );
/**
 * Display table created by "Advanced Custom Fields: Table Field" plugin
 */
function image_set( $atts ) {

	$a = shortcode_atts( array(
		'id' => '',
		'size' => '',
		'align' => ''
	), $atts );

	if ( ! $a['id'] ) {
		return;
	}

	// insert_image_srcset() expects and array with all params in [0] as a string
	$attributes[0] = '';

	foreach ( $a as $param => $value ) {

		$attributes[0] .= $param . '=' . $value . ', ';

	}

	return insert_image_srcset( $attributes );



}


/**
 * Look for shortcode, replace with content.
 *
 * Regular shortcodes don't work in ACF Table field, so this instead.
 *
 * @param $cell_content
 *
 * @return array
 * @since   1.0.0
 *
 */
function filter_responsive_image_output( $cell_content ) {

	return preg_replace_callback( '/\[ImageSet.*?]/i', __NAMESPACE__ . '\insert_image_srcset', $cell_content );

}


/**
 * Replace all occurences of `ImageSet` shortcode with an img tag
 *
 * Used by:
 * 1) ImageSet (regular shortcode)
 * 2) filter_responsive_image_output()  (custom `shortcode`)
 *
 * @param $custom_shortcode
 *
 * @return string
 * @since   1.0.0
 *
 */
function insert_image_srcset( $custom_shortcode ) {

	// TODO: escape ?? and elsewhere ??

	// Get ID
	preg_match( '/id=["\']?([\w-]+)/i', $custom_shortcode[0], $ids );

	// Get Size
	preg_match( '/size=["\']?([\w-]+)/i', $custom_shortcode[0], $sizes );

	// Get Alignment
	preg_match( '/align=["\']?([\w-]+)/i', $custom_shortcode[0], $alignments );

	// Capture group is always in [1]
	$this_id   = $ids[1] ?? '';
	$this_size = $sizes[1] ?? '';
	$this_alignment = $alignments[1] ?? '';

	// Sanity check (ID)
	if ( ! $this_id ) {
		// Something went wrong, don't replace the string
		return $custom_shortcode[0];
	}

	// Get intermediate image sizes, add full size
	$sizes_array = get_intermediate_image_sizes();
	$sizes_array[] = 'full';

	// Get size + also maybe make class
	$this_size = in_array( $this_size, $sizes_array ) ? $this_size : 'thumbnail';
	$size_class = ' size-' . $this_size;

	// Make alignment class
	$align_class = in_array( $this_alignment, array( 'left', 'right', 'center' ) )
		? 'align' . $this_alignment
		: 'alignnone';

	// Get src url + sizes
	$src_and_meta_array = wp_get_attachment_image_src( $this_id, $this_size );
	$src = $width = $height = '';
	if ( $src_and_meta_array ) {

		$src = $src_and_meta_array[0];
		$width = $src_and_meta_array[1];
		$height = $src_and_meta_array[2];

	}

	// Get srcset
	$srcset = wp_get_attachment_image_srcset( $this_id, $this_size );

	// Get sizes
	$sizes = wp_get_attachment_image_sizes( $this_id, $this_size );

	// Get caption
	$caption = wp_get_attachment_caption( $this_id );


	// Layout img tag
	$img_element = '<figure class="wp-caption">';
	$img_element .= '<img class="' . $align_class . $size_class . '" ';
	$img_element .= 'src="' . $src . '" ';
	$img_element .= 'alt width="' . $width . '" height="' . $height . '" ';
	$img_element .= 'srcset="' . $srcset . '" sizes="' . $sizes . '">';
	$img_element .= '<figcaption class="wp-caption-text">';
	$img_element .= $caption;
	$img_element .= '</figcaption></figure>';

	return $img_element;

}


