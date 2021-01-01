<?php
/**
 * Custom post type Creature
 *
 * @package     ParkdaleWire\DTS_Core
 * @since       1.0.0
 * @author      thartl
 * @link        https://parkdalewire.com
 * @license     GNU General Public License 2.0+
 */

namespace ParkdaleWire\DTS_Core;

/**
 * Species custom post type + helpers
 *
 * Based on: https://github.com/billerickson/Core-Functionality/blob/master/inc/cpt-testimonial.php
 *
 * @since 2.0.0
 */
class CPT_Species {

	/**
	 * Initialize all the things
	 *
	 * @since 2.0.0
	 */
	function __construct() {

		// Actions
		add_action( 'init', array( $this, 'register_tax' ) );
		add_action( 'init', array( $this, 'register_cpt' ) );

		// Yep, "gettext" is a filter...
		add_action( 'gettext', array( $this, 'title_placeholder' ) );

		add_action( 'pre_get_posts', array( $this, 'species_query' ) );
		add_action( 'template_redirect', array( $this, 'redirect_single' ) );

		// Column Filters
		add_filter( 'manage_edit-species_columns', array( $this, 'species_columns' ) );

		// Column Actions
		add_action( 'manage_species_pages_custom_column', array( $this, 'custom_columns' ), 10, 2 );
		add_action( 'manage_species_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );

		// Sortable Columns
		add_filter( 'manage_edit-species_sortable_columns', array( $this, 'species_sortable_columns' ) );

	}


	/**
	 * Register the taxonomies
	 *
	 * @since 2.0.0
	 */
	function register_tax() {

		$labels = array(
			'name'                       => 'Elevation',
			'singular_name'              => 'Elevation',
			'search_items'               => 'Search Elevations',
			'popular_items'              => 'Popular Elevations',
			'all_items'                  => 'All Elevations',
			'parent_item'                => 'Parent Elevation',
			'parent_item_colon'          => 'Parent Elevation:',
			'edit_item'                  => 'Edit Elevation',
			'update_item'                => 'Update Elevation',
			'add_new_item'               => 'Add New Elevation',
			'new_item_name'              => 'New Elevation',
			'separate_items_with_commas' => 'Separate Elevations with commas',
			'add_or_remove_items'        => 'Add or remove Elevations',
			'choose_from_most_used'      => 'Choose from most used Elevations',
			'menu_name'                  => 'Elevations',
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'show_in_nav_menus'  => false,
			'show_ui'            => true,
			'show_in_quick_edit' => false,
			'show_tagcloud'      => false,
			'hierarchical'       => false,
			'rewrite'            => array( 'slug' => 'species/elevations', 'with_front' => false ),
			'query_var'          => true,
			'show_admin_column'  => true,
			// 'meta_box_cb'    => false,
		);

		register_taxonomy( 'elevation', array( 'species' ), $args );


		$labels = array(
			'name'                       => 'Habitats',
			'singular_name'              => 'Habitat',
			'search_items'               => 'Search Habitats',
			'popular_items'              => 'Popular Habitats',
			'all_items'                  => 'All Habitats',
			'parent_item'                => 'Parent Habitat',
			'parent_item_colon'          => 'Parent Habitat:',
			'edit_item'                  => 'Edit Habitat',
			'update_item'                => 'Update Habitat',
			'add_new_item'               => 'Add New Habitat',
			'new_item_name'              => 'New Habitat',
			'separate_items_with_commas' => 'Separate Habitats with commas',
			'add_or_remove_items'        => 'Add or remove Habitats',
			'choose_from_most_used'      => 'Choose from most used Habitats',
			'menu_name'                  => 'Habitats',
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'show_in_nav_menus'  => true,
			'show_ui'            => true,
			'show_in_quick_edit' => true,
			'show_tagcloud'      => false,
			'hierarchical'       => false,
			'rewrite'            => array( 'slug' => 'species/landforms', 'with_front' => false ),
			'query_var'          => true,
			'show_admin_column'  => true,
			// 'meta_box_cb'    => false,
		);

		register_taxonomy( 'landform', array( 'species' ), $args );

	}

	/**
	 * Register the custom post type
	 *
	 * @since 2.0.0
	 */
	function register_cpt() {

		$labels = array(
			'name'               => 'Species',
			'singular_name'      => 'Species',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Species',
			'edit_item'          => 'Edit Species',
			'new_item'           => 'New Species',
			'view_item'          => 'View Species',
			'search_items'       => 'Search Species',
			'not_found'          => 'No Species found',
			'not_found_in_trash' => 'No Species found in Trash',
			'parent_item_colon'  => 'Parent Species:',
			'menu_name'          => 'Species',
		);

		$args = array(
			'labels'              => $labels,
			'hierarchical'        => false,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => array( 'slug' => 'species', 'with_front' => false ),
			'menu_icon'           => 'dashicons-admin-site', // https://developer.wordpress.org/resource/dashicons/
		);

		register_post_type( 'species', $args );

	}

	/**
	 * Change the default title placeholder text
	 *
	 * @param string $translation
	 *
	 * @return string Customized translation for title
	 * @since 2.0.0
	 * @global array $post
	 */
	function title_placeholder( $translation ) {

		global $post;
		if ( isset( $post ) && 'species' == $post->post_type && 'Add title' == $translation ) {
			$translation = 'Enter Name Here';
		}

		return $translation;

	}

	/**
	 * Customize the Species Query
	 *
	 * @param object $query
	 *
	 * @since 2.0.0
	 */
	function species_query( $query ) {
		if ( $query->is_main_query() && ! is_admin() && $query->is_post_type_archive( 'species' ) ) {
			$query->set( 'posts_per_page', 20 );
		}
	}

	/**
	 * Redirect Single Species
	 *
	 * @since 2.0.0
	 */
	function redirect_single() {
		if ( is_singular( 'species' ) ) {
//			wp_redirect( get_post_type_archive_link( 'species' ) );
			wp_redirect( home_url( '/field-guide/' ) );
			exit;
		}
	}

	/**
	 * Species custom columns
	 *
	 * @param array $columns
	 *
	 * @return array
	 * @since 2.0.0
	 *
	 */
	function species_columns( $columns ) {

		$columns = array(
			'cb'             => '<input type="checkbox" />',
			'title'          => 'Name',
			'species_number' => 'Number',
			'species_type'   => 'Type',
			'elevations'     => 'Elevations',
			'food'           => 'Food',
			'landforms'      => 'Habitats',
			'date'           => 'Date',
		);

		return $columns;
	}

	/**
	 * Cases for the custom columns
	 *
	 * @param array  $column
	 * @param int    $post_id
	 *
	 * @since 1.2.0
	 * @global array $post
	 */
	function custom_columns( $column, $post_id ) {

		switch ( $column ) {
			case 'species_number' :

				$species_number = esc_html( get_post_meta( $post_id, 'species_number', true ) );

				echo $species_number ?: '-';

				break;

			case 'species_type' :

				$species_type = esc_html( get_post_meta( $post_id, 'species_type', true ) );

				echo $species_type ?: '-';

				break;

			case 'elevations' :

				$terms = get_the_term_list( $post_id, 'elevation', '', ', ', '' );
				echo is_string( $terms ) ? $terms : '—';

				break;

			case 'food' :

				$food_number = esc_html( get_post_meta( $post_id, 'species_food', true ) );

				echo $food_number ?: '-';

				break;

			case 'landforms' :

				$terms = get_the_term_list( $post_id, 'landform', '', ', ', '' );
				echo is_string( $terms ) ? $terms : '—';

				break;

			default :
				break;

		}
	}


	function species_sortable_columns( $columns ) {

		$columns['species_number'] = 'species_number';

		return $columns;

	}

}

new CPT_Species();