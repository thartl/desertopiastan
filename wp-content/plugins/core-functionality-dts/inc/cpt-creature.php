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

/**
 * Creatures custom post type + helpers
 *
 * Based on: https://github.com/billerickson/Core-Functionality/blob/master/inc/cpt-testimonial.php
 *
 * @since 2.0.0
 */
class CPT_Creatures {

	/**
	 * Initialize all the things
	 *
	 * @since 2.0.0
	 */
	function __construct() {

		// Actions
		add_action( 'init', array( $this, 'register_tax' ) );
		add_action( 'init', array( $this, 'register_cpt' ) );
		add_action( 'gettext', array( $this, 'title_placeholder' ) );
		add_action( 'pre_get_posts', array( $this, 'creature_query' ) );
//		add_action( 'template_redirect', array( $this, 'redirect_single' ) );

		// Column Filters
		add_filter( 'manage_edit-creature_columns', array( $this, 'creature_columns' ) );

		// Column Actions
		add_action( 'manage_creature_pages_custom_column', array( $this, 'custom_columns' ), 10, 2 );
		add_action( 'manage_creature_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );
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
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => false,
			'hierarchical'      => false,
			'rewrite'           => array( 'slug' => 'creatures/elevations', 'with_front' => false ),
			'query_var'         => true,
			'show_admin_column' => false,
			// 'meta_box_cb'    => false,
		);

		register_taxonomy( 'elevation', array( 'creature' ), $args );
	}

	/**
	 * Register the custom post type
	 *
	 * @since 2.0.0
	 */
	function register_cpt() {

		$labels = array(
			'name'               => 'Creatures',
			'singular_name'      => 'Creature',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Creature',
			'edit_item'          => 'Edit Creature',
			'new_item'           => 'New Creature',
			'view_item'          => 'View Creature',
			'search_items'       => 'Search Creatures',
			'not_found'          => 'No Creatures found',
			'not_found_in_trash' => 'No Creatures found in Trash',
			'parent_item_colon'  => 'Parent Creature:',
			'menu_name'          => 'Creatures',
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
			'rewrite'             => array( 'slug' => 'creatures', 'with_front' => false ),
			'menu_icon'           => 'dashicons-groups', // https://developer.wordpress.org/resource/dashicons/
		);

		register_post_type( 'creature', $args );

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
		if ( isset( $post ) && 'creature' == $post->post_type && 'Enter title here' == $translation ) {
			$translation = 'Enter Name Here';
		}

		return $translation;

	}

	/**
	 * Customize the Creatures Query
	 *
	 * @param object $query
	 *
	 * @since 2.0.0
	 */
	function creature_query( $query ) {
		if ( $query->is_main_query() && ! is_admin() && $query->is_post_type_archive( 'creature' ) ) {
			$query->set( 'posts_per_page', 20 );
		}
	}

	/**
	 * Redirect Single Creatures
	 *
	 * @since 2.0.0
	 */
	function redirect_single() {
		if ( is_singular( 'creature' ) ) {
			wp_redirect( get_post_type_archive_link( 'creature' ) );
			exit;
		}
	}

	/**
	 * Creatures custom columns
	 *
	 * @param array $columns
	 *
	 * @return array
	 * @since 2.0.0
	 *
	 */
	function creature_columns( $columns ) {

		$columns = array(
			'cb'        => '<input type="checkbox" />',
			'thumbnail' => 'Thumbnail',
			'title'     => 'Name',
			'date'      => 'Date',
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

		global $post;

		switch ( $column ) {
			case 'thumbnail':
				the_post_thumbnail( 'thumbnail' );
				break;
		}
	}

}

new CPT_Creatures();