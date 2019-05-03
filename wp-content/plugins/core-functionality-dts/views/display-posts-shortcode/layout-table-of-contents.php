<?php
/**
 * "Table of Contents" layout for Display Posts Shortcode
 *
 * @package
 * @author       Tomas Hartl
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace ParkdaleWire\DTS_Core;


echo '<li class="listing-item">';


printf( '<a class="title" href="%1$s">%2$s</a>',
	get_anchor_url(),
	get_the_title()
);


if ( needs_spoiler_alert() ) {

    printf( '<p class="spoiler-link spoiler-alert">Full content: <a href="%s">Spoilers</a></p>',
	    get_permalink()
    );

}


echo '</li>';
