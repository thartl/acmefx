<?php
/**
 * Custom post type Credits
 *
 * @package     ParkdaleWire\AcmeFxCore
 * @since       1.0.0
 * @author      thartl
 * @link        https://parkdalewire.com
 * @license     GNU General Public License 2.0+
 */

namespace ParkdaleWire\AcmeFxCore;

/**
 * Credits custom post type + helpers
 *
 * Based on: https://github.com/billerickson/Core-Functionality/blob/master/inc/cpt-testimonial.php
 *
 * @since 1.0.0
 */
class CPT_Credits {

	/**
	 * Initialize all the things.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		// Actions
		add_action( 'init', array( $this, 'register_tax' ) );
		add_action( 'init', array( $this, 'register_cpt' ) );
		add_action( 'gettext', array( $this, 'title_placeholder' ) );
		add_action( 'pre_get_posts', array( $this, 'credits_query' ) );
		add_action( 'template_redirect', array( $this, 'redirect_single' ) );

		// Set up Credit columns
		add_filter( 'manage_edit-credits_columns', array( $this, 'set_up_credits_columns' ) );

		// Populate Credit columns
		add_action( 'manage_credits_pages_custom_column', array( $this, 'populate_credits_columns' ), 10, 2 );
		add_action( 'manage_credits_posts_custom_column', array( $this, 'populate_credits_columns' ), 10, 2 );

		// Set up sorting for Credit columns (sorting by 'release_date')
		add_filter( 'manage_edit-credits_sortable_columns', array( $this, 'make_credits_columns_sortable' ) );

		// Set up logic for sorting of Credit Columns
		add_action( 'load-edit.php', array( $this, 'sort_credits_by_release_date' ) );

		// Set up filter drop-down for Credit columns (filtering by 'credit_share')
		add_action( 'restrict_manage_posts', array( $this, 'filter_by_credit_share_tax_DROPDOWN' ), 10, 2 );

		// Set up logic to filter by 'credit_share' taxonomy term
		add_filter( 'parse_query', array( $this, 'filter_by_credit_share_tax_LOGIC' ), 10 );

	}


	/**
	 * Register the taxonomy.
	 *
	 * @since 1.0.0
	 */
	function register_tax() {

		$labels = array(
			'name'          => 'Credit Shares',
			'singular_name' => 'Credit Share',
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'show_in_menu'       => false,
			'show_in_quick_edit' => false,
			'show_tagcloud'      => false,
			'hierarchical'       => false,
			"rewrite"            => array( 'slug' => 'credit_share', 'with_front' => true, ),
			'query_var'          => true,
			'show_admin_column'  => true,
			"show_in_rest"       => false,
		);

		register_taxonomy( 'credit_share', array( 'credits' ), $args );

	}


	/**
	 * Register the custom post type.
	 *
	 * @since 1.0.0
	 */
	function register_cpt() {

		$labels = array(
			'name'               => 'Credits',
			'singular_name'      => 'Credit',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Credit',
			'edit_item'          => 'Edit Credit',
			'new_item'           => 'New Credit',
			'view_item'          => 'View Credit',
			'search_items'       => 'Search Credits',
			'not_found'          => 'No Credits found',
			'not_found_in_trash' => 'No Credits found in Trash',
			'parent_item_colon'  => 'Parent Credits:',
			'menu_name'          => 'Credits',
		);

		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'hierarchical'        => false,
			'supports'            => array( 'title' ),
			'show_ui'             => true,
			'show_in_menu'        => true,
			"menu_position"       => 20,
			'show_in_nav_menus'   => true,
			"delete_with_user"    => false,
			'publicly_queryable'  => true,
			"exclude_from_search" => false,
			"capability_type"     => "post",
			"map_meta_cap"        => true,
			"has_archive"         => false,
			'query_var'           => true,
			'can_export'          => true,
			"rewrite"             => array( 'slug' => 'credits', 'with_front' => true ),
			'menu_icon'           => 'dashicons-images-alt', // https://developer.wordpress.org/resource/dashicons/
			"show_in_rest"        => false,
		);

		register_post_type( 'credits', $args );

	}

	/**
	 * Change the default title placeholder text.
	 *
	 * @param string $translation
	 *
	 * @return string Customized translation for title
	 * @since 1.0.0
	 * @global array $post
	 */
	function title_placeholder( $translation ) {

		global $post;
		if ( isset( $post ) && 'credits' == $post->post_type && 'Add title' == $translation ) {
			$translation = 'Enter Credit title';
		}

		return $translation;

	}

	/**
	 * Customize the Species Query.
	 *
	 * @param object $query
	 *
	 * @since 1.0.0
	 */
	function credits_query( $query ) {
		if ( $query->is_main_query() && ! is_admin() && $query->is_post_type_archive( 'credit' ) ) {
			$query->set( 'posts_per_page', 20 );
		}
	}

	/**
	 * Redirect single Credits
	 *
	 * @since 1.0.0
	 */
	function redirect_single() {
		if ( is_singular( 'credits' ) ) {
			wp_redirect( home_url( '/acme-fx-toronto/' ) );
			exit;
		}
	}

	/**
	 * Set up Credits custom admin columns
	 *
	 * @param array $columns
	 *
	 * @return array
	 * @since 1.0.0
	 *
	 */
	function set_up_credits_columns( $columns ) {

		$columns = array(
			'cb'             => '<input type="checkbox" />',
			'title'          => 'Project',
			'release_date'   => 'Release date',
			'front_end_date' => 'Display date',
			'project_type'   => 'Type',
			// 'checked' => 'Checked',
			'credit_share'   => 'Credit Share',
			'date'           => 'Date',
		);

		return $columns;
	}

	/**
	 * Populate Credits custom admin columns
	 *
	 * @param array  $column
	 * @param int    $post_id
	 *
	 * @since 1.0.0
	 * @global array $post
	 */
	function populate_credits_columns( $column, $post_id ) {

		switch ( $column ) {

			case 'release_date' :

				$release_date = (int) get_post_meta( $post_id, 'release_date', true );
				$add_dash     = substr_replace( $release_date, '-', 4, 0 );
				$nice_date    = substr_replace( $add_dash, '-', 7, 0 );

				if ( empty( $release_date ) ) {
					echo( '--' );
				} else {
					echo( $nice_date );
				}

				break;


			case 'front_end_date' :

				$release_date   = (int) get_post_meta( $post_id, 'release_date', true );
				$year           = substr( $release_date, 0, 4 );
				$front_end_date = esc_html( get_post_meta( $post_id, 'front_end_date', true ) );
				$show_date      = $front_end_date ? $front_end_date : $year;

				if ( empty( $show_date ) ) {
					echo( '-' );
				} else {
					echo( $show_date );
				}

				break;


			case 'project_type' :

				$project_type = esc_html( get_post_meta( $post_id, 'project_type', true ) );

				if ( empty( $project_type ) ) {
					echo( 'empty!' );
				} else {
					echo( $project_type );
				}

				break;


			// Display a list of taxonomy terms assigned to the post
			case 'credit_share' :

				$terms = get_the_term_list( $post_id, 'credit_share', '', ', ', '' );
				echo is_string( $terms ) ? $terms : '—';

				break;


			default :
				break;

		}
	}

	/**
	 * Allow sorting by Release Date column
	 *
	 * @param $columns
	 *
	 * @return mixed
	 * @since   1.0.0
	 *
	 */
	function make_credits_columns_sortable( $columns ) {

		$columns['release_date'] = 'release_date';

		return $columns;

	}

	/**
	 * This function is attached to the 'load-edit.php' hook.
	 * It attaches a filter, which facilitates sorting.
	 *
	 * @return void
	 * @since   1.0.0
	 *
	 */
	function sort_credits_by_release_date() {

		add_filter( 'request', array( $this, 'sort_credits_by_release_date_FILTER' ) );

	}

	/**
	 * Modifies query to sort by meta key 'release_date'.
	 *
	 * @param $vars
	 *
	 * @return array
	 * @since   1.0.0
	 *
	 */
	function sort_credits_by_release_date_FILTER( $vars ) {

		if ( isset( $vars['post_type'] ) && 'credits' == $vars['post_type'] ) {

			if ( isset( $vars['orderby'] ) && 'release_date' == $vars['orderby'] ) {

				/* Merge the query vars with our custom variables. */
				$vars = array_merge(
					$vars,
					array(
						'meta_key' => 'release_date',
						'orderby'  => 'meta_value_num'
					)
				);
			}
		}

		return $vars;

	}

	/**
	 * Set up drop-down filter for 'credit_share' taxonomy.
	 *
	 * @param $post_type
	 * @param $which
	 *
	 * @return void
	 * @since   1.0.0
	 *
	 */
	function filter_by_credit_share_tax_DROPDOWN( $post_type, $which ) {

		if ( 'credits' !== $post_type ) {
			return;
		}

		$taxonomy_slug = 'credit_share';
		$taxonomy = get_taxonomy($taxonomy_slug);

		$selected      = '';
		$request_attr  = 'credit_share_filter'; //this will show up in the url

		if ( isset( $_REQUEST[ $request_attr ] ) ) {
			$selected = $_REQUEST[ $request_attr ]; //in case the current page is already filtered
		}

		wp_dropdown_categories( array(
			'show_option_all' => "Show All {$taxonomy->label}",
			'taxonomy'        => $taxonomy_slug,
			'name'            => $request_attr,
			'orderby'         => 'name',
			'selected'        => $selected,
			'hierarchical'    => true,
			'depth'           => 3,
			'show_count'      => true, // Show number of post in parent term
			'hide_empty'      => false, // Don't show posts w/o terms
		) );

	}

	/**
	 * Adjust query to filter by selected 'credit_share' taxonomy term, if any.
	 *
	 * @param $query
	 *
	 * @return mixed
	 * @since   1.0.0
	 *
	 */
	function filter_by_credit_share_tax_LOGIC( $query ) {

		if ( ! is_admin() ) {
			return $query;
		}

		if ( $query->is_main_auery() ) {
			return $query;
		}

		if ( 'credits' !== $query->query['post_type'] ) {
			return $query;
		}

		// query_var 'credit_share_filter' is set to '0' when "Show All Credit Shares" is selected
		if ( isset( $_REQUEST['credit_share_filter'] ) && 0 != $_REQUEST['credit_share_filter'] ) {

			$term          = $_REQUEST['credit_share_filter'];
			$taxonomy_slug = 'credit_share';

			$query->query_vars['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy_slug,
					'field'    => 'ID',
					'terms'    => array( $term )
				)
			);

		}

		return $query;

	}


}


new CPT_Credits();
