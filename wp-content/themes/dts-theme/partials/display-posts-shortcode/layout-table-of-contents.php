<?php
/**
 * "Table of Contents" layout for Display Posts Shortcode
 *
 * @package
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/


$post_listing_anchor = '/guide/#post-' . $post->post_name;

$url = esc_url( home_url( $post_listing_anchor ) );


echo '<li class="listing-item">';
echo '<a class="title" href="' . $url . '">' . get_the_title() . '</a>';
echo '</li>';