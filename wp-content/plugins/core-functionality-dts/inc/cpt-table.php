<?php
/**
 * Custom post type Table
 *
 * @package     ParkdaleWire\DTS_Core
 * @since       1.0.0
 * @author      thartl
 * @link        https://parkdalewire.com
 * @license     GNU General Public License 2.0+
 */

namespace ParkdaleWire\DTS_Core;

/**
 * Table custom post type + helpers
 *
 * Based on: https://github.com/billerickson/Core-Functionality/blob/master/inc/cpt-testimonial.php
 *
 * @since 2.0.0
 */
class CPT_Table {

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
		add_action( 'pre_get_posts', array( $this, 'table_query' ) );
		add_action( 'template_redirect', array( $this, 'redirect_single' ) );

		// Column Filters
		add_filter( 'manage_edit-table_columns', array( $this, 'table_columns' ) );

		// Column Actions
		add_action( 'manage_table_pages_custom_column', array( $this, 'custom_columns' ), 10, 2 );
		add_action( 'manage_table_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );

		// Sortable Columns
		add_filter( 'manage_edit-table_sortable_columns', array( $this, 'table_sortable_columns' ) );

	}


	/**
	 * Register the custom post type
	 *
	 * @since 2.0.0
	 */
	function register_cpt() {

		$labels = array(
			'name'               => 'Tables',
			'singular_name'      => 'Table',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Table',
			'edit_item'          => 'Edit Table',
			'new_item'           => 'New Table',
			'view_item'          => 'View Table',
			'search_items'       => 'Search Tables',
			'not_found'          => 'No Table found',
			'not_found_in_trash' => 'No Table found in Trash',
			'parent_item_colon'  => 'Parent Table:',
			'menu_name'          => 'Table',
		);

		$args = array(
			'labels'              => $labels,
			'hierarchical'        => false,
			'supports'            => array( 'title' ),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => array( 'slug' => 'table', 'with_front' => false ),
			'menu_icon'           => 'dashicons-groups', // https://developer.wordpress.org/resource/dashicons/
		);

		register_post_type( 'table', $args );

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
		if ( isset( $post ) && 'table' == $post->post_type && 'Enter title here' == $translation ) {
			$translation = 'Enter Table Name Here';
		}

		return $translation;

	}

	/**
	 * Customize the Table Query
	 *
	 * @param object $query
	 *
	 * @since 2.0.0
	 */
	function table_query( $query ) {
		if ( $query->is_main_query() && ! is_admin() && $query->is_post_type_archive( 'table' ) ) {
			$query->set( 'posts_per_page', 20 );
		}
	}

	/**
	 * Redirect Single Table
	 *
	 * @since 2.0.0
	 */
	function redirect_single() {
		if ( is_singular( 'table' ) ) {
//			wp_redirect( get_post_type_archive_link( 'table' ) );
			wp_redirect( home_url() );
			exit;
		}
	}

	/**
	 * Table custom columns
	 *
	 * @param array $columns
	 *
	 * @return array
	 * @since 2.0.0
	 *
	 */
	function table_columns( $columns ) {

		$columns = array(
			'cb'           => '<input type="checkbox" />',
			'title'        => 'Name',
			'table_id'     => 'Table ID',
			'date'         => 'Date',
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
			case 'table_id' :

				$table_number = esc_html( get_post_meta( $post_id, 'table_id', true ) );

				echo $table_number ?: '-';

				break;

			default :
				break;

		}
	}


	function table_sortable_columns( $columns ) {

		$columns['table_id'] = 'table_id';

		return $columns;

	}

}

new CPT_Table();