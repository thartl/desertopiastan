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

	$all_tables    = get_posts( $args );

	// Get the latest table's table_id
	$last_table_post_ID = isset( $all_tables[0] ) ? abs( (int) $all_tables[0]->ID ) : 0;
	$last_table_ID = (int) get_post_meta( $last_table_post_ID, 'table_id', true );

	wp_reset_postdata();


	// Get highest known / recorded table ID
	$highest_known_ID = abs( (int) get_option( 'pw_highest_table_id' ) );

	$new_ID = max( $highest_known_ID, $last_table_ID );
	$new_ID ++;

	// Set this table ID + update the highest known table ID
	update_post_meta( $post_id, 'table_id', $new_ID );
	update_option( 'pw_highest_table_id', $new_ID, false );

}


