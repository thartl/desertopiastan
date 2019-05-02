<?php
/**
 * "Table of Contents" layout for Display Posts Shortcode
 *
 * @package
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace ParkdaleWire\DesertopiaStan;


$post_ID = $post->ID;

$number = (int) get_post_meta( $post_ID, 'species_number', true );

$food = get_post_meta( $post_ID, 'species_food', true ) ?: '';

$elevations = get_the_terms( $post_ID, 'elevation' );

// Assemble array of elevations
$elevation_array = array();


if ( $elevations ) {

	foreach ( $elevations as $elevation ) {
		$elevation_array[] = (int) $elevation->name;
	}

	sort( $elevation_array );

	// Get lowest elevation
	$lowest_elevation = $elevation_array[0] ?: false;
	
	// Get highest elevation
	$highest_elevation = array_pop( $elevation_array );
	$highest_elevation = $highest_elevation == 8 ? 7 : $highest_elevation;

	$display_elevations = $lowest_elevation ?: '-';

	if ( $highest_elevation > $lowest_elevation ) {

		$display_elevations .= ' - ' . $highest_elevation;

	}

} else {

	$display_elevations = '-';

}


?>

<div class="species-item clearfix">
    <div class="info clearfix">
        <div class="main-image"><?php the_post_thumbnail( 'thumbnail' ) ?></div>
        <div class="number"># <?php echo $number; ?></div>
        <h3 class="title"><?php echo get_the_title(); ?></h3>
        <p class="food">Food: <span><?php echo $food; ?></span></p>
        <p>Elevations: <span><?php echo $display_elevations; ?></span></p>
    </div>
</div>