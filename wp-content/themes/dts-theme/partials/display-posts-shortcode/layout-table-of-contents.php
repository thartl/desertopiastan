<?php
/**
 * "Small" layout for Display Posts Shortcode
 *
 * @package
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/


//d( $post );
//d( $post->post_name );

global $wp;

$post_listing_anchor = '#post-' . $post->post_name;


echo '<li class="listing-item">';
echo '<a class="title" href="' . $post_listing_anchor . '">' . get_the_title() . '</a>';
echo '</li>';