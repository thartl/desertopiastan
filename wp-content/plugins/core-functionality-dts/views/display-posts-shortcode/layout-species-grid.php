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


?>

<div class="species-item clearfix<?php species_type_class();
habitat_classes() ?>">
    <div class="info clearfix">
        <div class="main-image"><?php the_post_thumbnail( 'thumbnail' ) ?></div>
        <div class="number"><?php species_number() ?></div>
        <h3 class="title"><?php echo get_the_title() ?></h3>
        <p>Height: <span class="elevation"><?php species_elevations() ?></span></p>
        <p class="food">Food: <span><?php species_food() ?></span></p>
    </div>
</div>