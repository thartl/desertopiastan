<?php
/**
 * Description
 *
 * @package     ParkdaleWire\DTS_Core
 * @since       1.0.0
 * @author      thartl
 * @link        https://parkdalewire.com
 * @license     GNU General Public License 2.0+
 */

namespace ParkdaleWire\DTS_Core;


if ( ! function_exists( __NAMESPACE__ . '\display_filters_group' ) ) {

	function display_filters_group() {

		$all_habitats = get_terms( array(
			'taxonomy' => 'landform',
			'hide_empty' => true,
		) );

//		d( $all_habitats );

        echo '<div class="button-group filters-button-group">';
		echo '<button class="button is-checked" data-filter="*">show all</button>';

		foreach ( $all_habitats as $habitat ) {

            printf('<button class="button" data-filter=".habitat-%1$s">%2$s</button>',
                $habitat->slug,
                $habitat->name
                );

        }

        echo '</div>';



	}

}


if ( ! function_exists( __NAMESPACE__ . '\display_species_grid' ) ) {

	function display_species_grid() {

		echo do_shortcode( '[display-posts post_type="species" image_size="thumbnail" posts_per_page="100" meta_key="species_number" orderby="meta_value_num" order="ASC" layout="species-grid" wrapper="div" wrapper_class="species-grid"]' );

	}

}


display_filters_group();

display_species_grid();