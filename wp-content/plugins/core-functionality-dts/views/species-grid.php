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


if ( ! function_exists( __NAMESPACE__ . '\get_habitats_array' ) ) {

	/**
	 * Returns array of arrays (habitats), each with a slug and a name of the `landform` taxonomy.
	 *
	 * @return false | array
	 */
	function get_habitats_array() {

		global $post;

		$habitats = get_the_terms( $post->ID, 'landform' );


		// Assemble array of habitats
		$habitats_array = array();

		if ( $habitats ) {

			$habitat_count = 0;
			foreach ( $habitats as $habitat ) {
				$habitats_array[ $habitat_count ][ 'slug' ] = $habitat->slug;
				$habitats_array[ $habitat_count ][ 'name' ] = $habitat->name;
				$habitat_count ++;
			}
			d( $habitats_array );

			return $habitats_array;

		} else {

			return false;

		}

	}

}


if ( ! function_exists( __NAMESPACE__ . '\display_filters_group' ) ) {

	function display_filters_group() {


		?>

        <div class="button-group filters-button-group">
            <button class="button is-checked" data-filter="*">show all</button>
            <button class="button" data-filter=".habitat-beach">Beach</button>
            <button class="button" data-filter=".transition">transition</button>
            <button class="button" data-filter=".alkali, .alkaline-earth">alkali and alkaline-earth</button>
            <button class="button" data-filter=":not(.transition)">not transition</button>
            <button class="button" data-filter=".metal:not(.transition)">metal but not transition</button>
        </div>

		<?php


	}

}


if ( ! function_exists( __NAMESPACE__ . '\display_species_grid' ) ) {

	function display_species_grid() {

		echo do_shortcode( '[display-posts post_type="species" image_size="thumbnail" posts_per_page="100" meta_key="species_number" orderby="meta_value_num" order="ASC" layout="species-grid" wrapper="div" wrapper_class="species-grid"]' );

	}

}


display_filters_group();

display_species_grid();