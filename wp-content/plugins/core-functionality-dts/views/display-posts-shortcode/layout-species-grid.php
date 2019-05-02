<?php
/**
 * "Species Grid" layout for Display Posts Shortcode
 *
 * @package
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace ParkdaleWire\DTS_Core;


if ( ! function_exists( __NAMESPACE__ . '\species_number' ) ) {

	function species_number() {
		global $post;
		echo (int) get_post_meta( $post->ID, 'species_number', true );
	}

}


if ( ! function_exists( __NAMESPACE__ . '\species_food' ) ) {

	function species_food() {
		global $post;
		echo (int) get_post_meta( $post->ID, 'species_food', true ) ?: '-';
	}

}


if ( ! function_exists( __NAMESPACE__ . '\species_type_class' ) ) {

	function species_type_class() {
		global $post;
		$type_class = esc_html( get_post_meta( $post->ID, 'species_type', true ) );

		echo $type_class ? ' species-' . strtolower( $type_class ) : '';

	}

}


if ( ! function_exists( __NAMESPACE__ . '\species_elevations' ) ) {

	/**
	 * Echoes range of elevations, or single elevation, or a dash for "no data".
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

				$display_elevations .= ' - ' . $highest_elevation;

				echo $display_elevations;

			}

		} else {

			echo '-';

		}

	}

}


?>

<div class="species-item clearfix<?php species_type_class();
habitat_classes() ?>">
    <div class="info clearfix">
        <div class="main-image"><?php the_post_thumbnail( 'thumbnail' ) ?></div>
        <div class="number">#<?php species_number() ?></div>
        <h3 class="title"><?php echo get_the_title() ?></h3>
        <p>Height: <span class="elevation"><?php species_elevations() ?></span></p>
        <p class="food">Food: <span><?php species_food() ?></span></p>
    </div>
</div>