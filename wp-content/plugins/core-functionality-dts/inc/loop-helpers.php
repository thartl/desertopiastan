<?php
/**
 * Loops helpers
 *
 * @package     ParkdaleWire\DTS_Core
 * @since       1.0.0
 * @author      thartl
 * @link        https://parkdalewire.com
 * @license     GNU General Public License 2.0+
 */

namespace ParkdaleWire\DTS_Core;


/**
 * Display the Species Main number (ID number)
 *
 * Works only within a Species loop.
 *
 * @echo    string  Species' Main number (ID number)
 *
 */
function species_number() {

	global $post;

	echo (int) get_post_meta( $post->ID, 'species_number', true );

}


/**
 * Display the Species food number
 *
 * Works only within a Species loop.
 *
 * @echo    string  Species' food number
 *
 */
function species_food() {

	global $post;

	echo (int) get_post_meta( $post->ID, 'species_food', true ) ?: '-';

}


/**
 * Get URL for the iteration of the current heading, which points to the post listing on the Guide page
 *
 * @return  string  Escaped URL
 *
 */
function get_anchor_url() {

	global $post;

	$post_listing_anchor = '/guide/#post-' . $post->post_name;

	return esc_url( home_url( $post_listing_anchor ) );

}


/**
 * Displays range of elevations, or single elevation, or a dash for "no data".
 *
 * Works only within a Species loop.
 *
 * @echo false | array
 */
function species_elevations() {

	global $post;

	$elevations = get_the_terms( $post->ID, 'elevation' );

	// Assemble array of elevations
	$elevation_array = array();


	if ( $elevations ) {

		foreach ( $elevations as $elevation ) {
			$elevation_array[] = (int) $elevation->name;
		}

		sort( $elevation_array );

		// Get lowest elevation
		$lowest_elevation = $elevation_array[0] ?: false;
		$lowest_elevation = $lowest_elevation == 8 ? 9 : $lowest_elevation;

		// Get highest elevation
		$highest_elevation = array_pop( $elevation_array );
		$highest_elevation = $highest_elevation == 8 ? 7 : $highest_elevation;

		$display_elevations = $lowest_elevation ?: '-';

		if ( $highest_elevation > $lowest_elevation ) {

			$display_elevations .= '-' . $highest_elevation;

		}

		echo $display_elevations;

	} else {

		echo '-';

	}

}


/**
 * Get Species' type and attach to species item as a class
 *
 * Works only within a Species loop.
 *
 * @echo    string  List of classes
 *
 */
function species_type_class() {
	global $post;
	$type_class = esc_html( get_post_meta( $post->ID, 'species_type', true ) );

	echo $type_class ? ' species-' . strtolower( $type_class ) : '';

}


/**
 * Prints a list of classes based on attached Habitats
 *
 * Works only within a Species loop.
 *
 * @echo string     List of classes
 */
function habitat_classes() {

	global $post;
	$habitats = get_the_terms( $post->ID, 'landform' );

	// Assemble array of habitats
	$habitats_array = array();

	if ( $habitats ) {

		foreach ( $habitats as $habitat ) {
			$habitats_array[] = 'habitat-' . $habitat->slug;
		}

		$habitat_classes = implode( ' ', $habitats_array );

		echo ' ' . $habitat_classes;

	} else {

		echo '';

	}

}


/**
 * Displays habitats icons
 *
 * @echo string     (HTML) Two button groups: Filter, Sort
 */
function display_habitat_icons( $type ) {

	switch ( strtolower( $type ) ) {

		case 'regular' :

			$meta_query_array = array( 'key' => 'habitat_type', 'value' => 'regular' );

			break;

		case 'special' :

			$meta_query_array = array( 'key' => 'habitat_type', 'value' => 'special' );

			break;

		default:

			$meta_query_array = array();

			break;

	}

	$all_habitats = get_terms( array(
		'taxonomy'   => 'landform',
		'hide_empty' => true,
		'meta_query' => array( $meta_query_array )
	) );

	foreach ( $all_habitats as $habitat ) {

		$term_image_ID = (int) get_term_meta( $habitat->term_id, 'habitat_image', true );

		$habitat_type = strtolower( esc_html( get_term_meta( $habitat->term_id, 'habitat_type', true ) ) );

        if ( $habitat_type ) {
            $habitat_class = array( 'class' => 'hab-' . $habitat_type . ' attachment-thumbnail size-thumbnail' );
        } else {
	        $habitat_class = array( 'class' => 'hab-na attachment-thumbnail size-thumbnail' );
        }


		$image_url = wp_get_attachment_image( $term_image_ID, 'thumbnail', false, $habitat_class )
            ?: '';

		echo $image_url;

	}

}


/**
 * Displays Filter and Sorting buttons for the Species Grid
 *
 * @echo string     (HTML) Two button groups: Filter, Sort
 */
function display_filter_sort_button_groups() {

	$all_habitats = get_terms( array(
		'taxonomy'   => 'landform',
		'hide_empty' => true,
	) );


	echo '<div class="button-group filters-button-group">';

	echo '<button class="button default is-checked" data-filter="*">All</button>';

	foreach ( $all_habitats as $habitat ) {

		$term_type       = esc_html( get_term_meta( $habitat->term_id, 'habitat_type', true ) );
		$term_type_class = $term_type ? ' habitat-' . strtolower( $term_type ) : '';

		$term_image_ID = (int) get_term_meta( $habitat->term_id, 'habitat_image', true );
		$image_url     = wp_get_attachment_image( $term_image_ID, 'thumbnail' ) ?: '';

		printf( '<button class="button%1$s" data-filter=".habitat-%2$s">%3$s</button>',
			$term_type_class,
			$habitat->slug,
			$image_url
		);

	}

	echo '</div>';

	?>

    <!-- <div class="button-group sort-by-button-group">
        <button class="button is-checked" data-sort-value="original-order">Number</button>
        <button class="button" data-sort-value="name">Name</button>
        <button class="button" data-sort-value="elevation">Height</button>
    </div> -->

	<?php

}


function display_species_grid() {

	echo do_shortcode( '[display-posts post_type="species" image_size="thumbnail" posts_per_page="100" meta_key="species_number" orderby="meta_value_num" order="ASC" layout="species-grid" wrapper="div" wrapper_class="species-grid"]' );

}


