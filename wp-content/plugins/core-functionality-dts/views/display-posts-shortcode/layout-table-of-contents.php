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
echo '<a class="title" href="' . get_anchor_url() . '">' . get_the_title() . '</a>';
echo '</li>';