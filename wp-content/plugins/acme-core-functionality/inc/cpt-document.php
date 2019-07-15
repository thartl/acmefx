<?php
/**
 * Custom post type Document
 *
 * @package     ParkdaleWire\AcmeFxCore
 * @since       1.0.0
 * @author      thartl
 * @link        https://parkdalewire.com
 * @license     GNU General Public License 2.0+
 */

namespace ParkdaleWire\AcmeFxCore;

/**
 * Sets up the Document custom post type + helpers
 *
 * Based on: https://github.com/billerickson/Core-Functionality/blob/master/inc/cpt-testimonial.php
 *
 * @since 1.0.0
 */
class CPT_Document {

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
		add_action( 'pre_get_posts', array( $this, 'document_query' ) );
		add_action( 'template_redirect', array( $this, 'redirect_single' ) );

		// Set up Document columns
		add_filter( 'manage_edit-document_columns', array( $this, 'set_up_document_columns' ) );

		// Populate Credit columns
		add_action( 'manage_document_pages_custom_column', array( $this, 'populate_document_columns' ), 10, 2 );
		add_action( 'manage_document_posts_custom_column', array( $this, 'populate_document_columns' ), 10, 2 );

		// Set up sorting for Document columns (sorting by 'release_date')
		add_filter( 'manage_edit-document_sortable_columns', array( $this, 'make_document_columns_sortable' ) );

		// Set up logic for sorting of Document Columns
		add_action( 'load-edit.php', array( $this, 'sort_documents_by_year_published' ) );

		// Set up filter drop-down for Document columns (filtering by 'doc_type')
		add_action( 'restrict_manage_posts', array( $this, 'filter_by_doc_type_tax_DROPDOWN' ), 10, 2 );

		// Set up logic to filter by 'doc_type' taxonomy term
		add_filter( 'parse_query', array( $this, 'filter_by_doc_type_tax_LOGIC' ), 10 );

	}


	/**
	 * Register the taxonomy.
	 *
	 * @since 1.0.0
	 */
	function register_tax() {

		$labels = array(
			'name'          => 'Document Types',
			'singular_name' => 'Document Type',
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'show_in_menu'       => false,
			'show_in_quick_edit' => false, // true will delete tax info when using Quick Edit
			'show_tagcloud'      => false,
			'hierarchical'       => false,
			"rewrite"            => array( 'slug' => 'doc-type', 'with_front' => true, ),
			'query_var'          => true,
			'show_admin_column'  => true,
			"show_in_rest"       => false,
		);

		register_taxonomy( 'doc_type', array( 'document' ), $args );

	}


	/**
	 * Register the custom post type.
	 *
	 * @since 1.0.0
	 */
	function register_cpt() {

		$labels = array(
			'name'               => 'Library Documents',
			'singular_name'      => 'Library Document',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Library Document',
			'edit_item'          => 'Edit Library Document',
			'new_item'           => 'New Library Document',
			'view_item'          => 'View Library Document',
			'search_items'       => 'Search Library Documents',
			'not_found'          => 'No Library Documents found',
			'not_found_in_trash' => 'No Library Documents found in Trash',
			'parent_item_colon'  => 'Parent Library Documents:',
			'menu_name'          => 'Library',
		);

		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'hierarchical'        => false,
			'supports'            => array( 'title' ),
			'show_ui'             => true,
			'show_in_menu'        => true,
			"menu_position"       => 21,
			'show_in_nav_menus'   => true,
			"delete_with_user"    => false,
			'publicly_queryable'  => true,
			"exclude_from_search" => false,
			"capability_type"     => "post",
			"map_meta_cap"        => true,
			"has_archive"         => false,
			'query_var'           => true,
			'can_export'          => true,
			"rewrite"             => array( 'slug' => 'documents', 'with_front' => true ),
			'menu_icon'           => 'dashicons-book-alt', // https://developer.wordpress.org/resource/dashicons/
			"show_in_rest"        => false,
		);

		register_post_type( 'document', $args );

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
		if ( isset( $post ) && 'document' == $post->post_type && 'Add title' == $translation ) {
			$translation = 'Enter Document title';
		}

		return $translation;

	}

	/**
	 * Customize the Document Query.
	 *
	 * @param object $query
	 *
	 * @since 1.0.0
	 */
	function document_query( $query ) {
		if ( $query->is_main_query() && ! is_admin() && $query->is_post_type_archive( 'document' ) ) {
			$query->set( 'posts_per_page', 20 );
		}
	}

	/**
	 * Redirect single Documents
	 *
	 * @since 1.0.0
	 */
	function redirect_single() {

		if ( is_singular( 'document' ) ) {

			$terms           = wp_get_post_terms( get_the_ID(), 'doc_type' );

			if ( $terms[0]->slug ) {
				$slug = $terms[0]->slug;
			} else {
				$slug = 'default';
			}

			$redirect_by_term_slug = array(
				'manual'     => 'manuals',
				'sds'        => 'sds',
				'spec-sheet' => 'spec-sheets',
				'default' => 'manuals'
			);

			$page = $redirect_by_term_slug[ $slug ];

			$page_slug = "/" . $page . "/";

			wp_redirect( home_url( $page_slug ) );
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
	function set_up_document_columns( $columns ) {

		$columns = array(
			'cb'             => '<input type="checkbox" />',
			'title'          => 'Document',
			'doc_type'       => 'Doc type',
			'file_name'      => 'PDF',
			'year_published' => 'Year published',
			'doc_page_count' => 'Pages',
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
	function populate_document_columns( $column, $post_id ) {

		switch ( $column ) {

			// Display a list of taxonomy terms assigned to the post
			case 'doc_type' :

				$terms = get_the_term_list( $post_id, 'doc_type', '', ', ', '' );
				echo is_string( $terms ) ? $terms : '—';

				break;


			case 'file_name' :

				$doc_id = (int) get_post_meta( get_the_ID(), 'doc_url', true );
				$title  = get_the_title( $doc_id );

				if ( ! $title ) {
					echo( '--' );
				} else {
					echo( $title );
				}

				break;


			case 'year_published' :

				$year_published = (int) get_post_meta( get_the_ID(), 'G-doc-info_year', true );

				if ( ! $year_published ) {
					echo( '----' );
				} else {
					echo( $year_published );
				}

				break;


			case 'doc_page_count' :

				$doc_page_count = (int) get_post_meta( get_the_ID(), 'G-doc-info_pages', true );

				if ( ! $doc_page_count ) {
					echo( '--' );
				} else {
					echo( $doc_page_count );
				}

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
	function make_document_columns_sortable( $columns ) {

		$columns['year_published'] = 'year_published';

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
	function sort_documents_by_year_published() {

		add_filter( 'request', array( $this, 'sort_documents_by_year_published_FILTER' ) );

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
	function sort_documents_by_year_published_FILTER( $vars ) {

		if ( isset( $vars['post_type'] ) && 'document' == $vars['post_type'] ) {

			if ( isset( $vars['orderby'] ) && 'year_published' == $vars['orderby'] ) {

				/* Merge the query vars with our custom variables. */
				$vars = array_merge(
					$vars,
					array(
						'meta_key' => 'G-doc-info_year',
						'orderby'  => 'meta_value_num'
					)
				);
			}
		}

		return $vars;

	}

	/**
	 * Set up drop-down filter for 'doc_type' taxonomy.
	 *
	 * @param $post_type
	 * @param $which
	 *
	 * @return void
	 * @since   1.0.0
	 *
	 */
	function filter_by_doc_type_tax_DROPDOWN( $post_type, $which ) {

		if ( 'document' !== $post_type ) {
			return;
		}

		$taxonomy_slug = 'doc_type';
		$taxonomy      = get_taxonomy( $taxonomy_slug );

		$selected     = '';
		$request_attr = 'doc_type_filter'; // this will show up in the url

		if ( isset( $_REQUEST[ $request_attr ] ) ) {
			$selected = $_REQUEST[ $request_attr ]; // in case the current page is already filtered
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
	 * Adjust query to filter by selected 'doc_type' taxonomy term, if any.
	 *
	 * @param $query
	 *
	 * @return mixed
	 * @since   1.0.0
	 *
	 */
	function filter_by_doc_type_tax_LOGIC( $query ) {

		if ( ! is_admin() ) {
			return $query;
		}

		if ( $query->is_main_auery() ) {
			return $query;
		}

		if ( 'document' !== $query->query['post_type'] ) {
			return $query;
		}

		// query_var 'doc_type_filter' is set to '0' when "Show All Document Types" is selected
		if ( isset( $_REQUEST['doc_type_filter'] ) && 0 != $_REQUEST['doc_type_filter'] ) {

			$term          = $_REQUEST['doc_type_filter'];
			$taxonomy_slug = 'doc_type';

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


new CPT_Document();
