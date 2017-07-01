<?php
/**
 * Acme FX.
 *
 * This file adds functions to the Acme FX theme.
 * This is overflow from functions.php
 *
 * @package Acme FX
 * @author  Parkdale Wire
 * @license GPL-2.0+
 * @link    http://www.studiopress.com/
 * @link    http://www.parkdalewire.com/
 */


/**
 * Change text strings, case by case
 *
 * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/gettext
 */
function th_change_text_strings( $translated_text, $text, $domain ) {
	switch ( $translated_text ) {
		case 'Share with Friends' :
			$translated_text = __( 'Share:', 'wc_wishlist' );
			break;
	}
	return $translated_text;
}
add_filter( 'gettext', 'th_change_text_strings', 20, 3 );


// Change the number of products per page -- this is default, used for Grid view
//add_filter( 'loop_shop_per_page', 'th_new_loop_shop_per_page', 20 );
function th_new_loop_shop_per_page( $cols ) {
  $cols = 12;
  return $cols;
}


/*********************************************************************************************************************************/
/************************ th-- WC Grid/List View *********************************************************************************/

// Set the number of products per page for Grid view and List view (default is set above in this file)
add_filter( 'loop_shop_per_page', 'th_list_view_loop_shop_per_page', 22 );
function th_list_view_loop_shop_per_page( $cols ) {
	if(!isset($_COOKIE['store_view']) || $_COOKIE['store_view'] == 'grid') {
		$cols = 12;  /**  products per page in Grid view  **/
		return $cols;
	} elseif( $_COOKIE['store_view'] == 'list' ) {
		$cols = 24;  /**  products per page in List view  **/
		return $cols;
	} else {
		return $cols;
	}
}


/************************ Place Grid/List View buttons ***************************************************************************/
add_action( 'woocommerce_before_shop_loop', 'th_grid_list_buttons', 18 );
function th_grid_list_buttons() {

if(!isset($_COOKIE['store_view']) || $_COOKIE['store_view'] == 'grid') {

	echo '<div class="grid-list-switch">';

} else {

	echo '<div class="grid-list-switch list-view">';

}

	echo '<div class="grid-view-btn" ><svg fill="#fff" class="grid-view-btn" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" x="0px" y="0px"><title>list-2</title><path d="M501,249v10a2,2,0,0,1-2,2H489a2,2,0,0,1-2-2V249a2,2,0,0,1,2-2h10A2,2,0,0,1,501,249Zm16-2H507a2,2,0,0,0-2,2v10a2,2,0,0,0,2,2h10a2,2,0,0,0,2-2V249A2,2,0,0,0,517,247Zm18,0H525a2,2,0,0,0-2,2v10a2,2,0,0,0,2,2h10a2,2,0,0,0,2-2V249A2,2,0,0,0,535,247Zm-36,18H489a2,2,0,0,0-2,2v10a2,2,0,0,0,2,2h10a2,2,0,0,0,2-2V267A2,2,0,0,0,499,265Zm18,0H507a2,2,0,0,0-2,2v10a2,2,0,0,0,2,2h10a2,2,0,0,0,2-2V267A2,2,0,0,0,517,265Zm18,0H525a2,2,0,0,0-2,2v10a2,2,0,0,0,2,2h10a2,2,0,0,0,2-2V267A2,2,0,0,0,535,265Zm-36,18H489a2,2,0,0,0-2,2v10a2,2,0,0,0,2,2h10a2,2,0,0,0,2-2V285A2,2,0,0,0,499,283Zm18,0H507a2,2,0,0,0-2,2v10a2,2,0,0,0,2,2h10a2,2,0,0,0,2-2V285A2,2,0,0,0,517,283Zm18,0H525a2,2,0,0,0-2,2v10a2,2,0,0,0,2,2h10a2,2,0,0,0,2-2V285A2,2,0,0,0,535,283Z" transform="translate(-487 -247)"></path></svg></div>' .

		'<div class="list-view-btn" ><svg fill="#fff" class="list-view-btn" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><path d="M4,0h92c2.209,0,4,1.791,4,4v12c0,2.209-1.791,4-4,4H4c-2.209,0-4-1.791-4-4V4C0,1.791,1.791,0,4,0z"></path><path d="M4,26.667h92c2.209,0,4,1.791,4,4v12c0,2.209-1.791,4-4,4H4c-2.209,0-4-1.791-4-4v-12
	C0,28.458,1.791,26.667,4,26.667z"></path><path d="M4,53.334h92c2.209,0,4,1.79,4,4v12c0,2.209-1.791,4-4,4H4c-2.209,0-4-1.791-4-4v-12
	C0,55.124,1.791,53.334,4,53.334z"></path><path d="M4,80h92c2.209,0,4,1.791,4,4v12c0,2.209-1.791,4-4,4H4c-2.209,0-4-1.791-4-4V84C0,81.791,1.791,80,4,80z"></path></svg></div>' .

		'</div><!-- div.grid-list-switch -->';

}


// Enqueue js-cookie on WC pages -- used to read and set cookies
add_action( 'wp_footer', 'th_enqueue_js_cookie', 10 );
function th_enqueue_js_cookie() {
		if 	( is_shop() || ( is_woocommerce() && is_archive() ) ) {
	wp_enqueue_script( 'js-cookie', get_stylesheet_directory_uri() . '/js.cookie.min.js', array( '' ), CHILD_THEME_VERSION );
		}
}


/** Grid/List buttons set cookies and reload page  **********************************************************************/
add_action( 'wp_footer', 'th_grid_list_switches', 100 );

	function th_grid_list_switches() { 

		if( is_shop() || ( is_woocommerce() && is_archive() ) ) {		

			?><script type="text/javascript">

				jQuery(function( $ ) {

					var store_view = Cookies.get( 'store_view' );

						$( "div.grid-view-btn" ).click( function() {
							if( store_view == 'list' ) {
								Cookies.set( 'store_view', 'grid', { expires: 30 } );
								window.location.reload();
							}
						});

						$( "div.list-view-btn" ).click( function() {
							if( store_view !== 'list' ) {
								Cookies.set( 'store_view', 'list', { expires: 30 } );
								window.location.reload();
							}
						});

				});

			</script><?php

		}

	}

/** END: Grid/List buttons set cookies and reload page  **********************************************************************/


/**** testing  ************************************************************/
// add_action( 'woocommerce_before_shop_loop', 'th_read_cookies', 16 );
// function th_read_cookies() {

// if(!isset($_COOKIE['store_view'])) {
// 	  echo '<br>The cookie: store_view is NOT set.';
// 	} else {
// 	  echo '<br>Cookie is:  <h3>' . $_COOKIE['store_view'] . '</h3><br>';
// 	}
// }
/*************************************************************************/


/************************ th-- END: WC Grid/List View ****************************************************************************/
/*********************************************************************************************************************************/



// For main shop page only:  attach shop page url to page title -- used to reset filters
add_filter( 'woocommerce_page_title', 'th_woocommerce_category_page_title', 10, 1 );
function th_woocommerce_category_page_title( $page_title ) {
	if ( is_shop() ) {
		$shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
		$url = esc_url( $shop_page_url );
		$heading = '<a href="' . $url . '" >' . $page_title . '</a>';

		return $heading;
	}
}



//  Add link to bottom of page if text continues
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description' );
add_action( 'woocommerce_archive_description', 'th_woocommerce_taxonomy_archive_description' );
	function th_woocommerce_taxonomy_archive_description() {

		global $wp_query;
		if ( is_product_category() || is_product_tag() ) {
			$term_id = get_queried_object()->term_id;
			$term_meta = get_term_meta( $term_id, 'intro_text', true );
			$term = get_term_by( 'id', $term_id, get_query_var( 'taxonomy' ) );
			$term_name = $term->name;

			$anchor_link = '';
			if ( !empty( $term_meta ) ) {
				$anchor_link = '<a href="#description-continued" class="cat-tag-continue-reading" >Continue reading about ' . $term_name . '</a>';
			}

			if ( is_product_taxonomy() && 0 === absint( get_query_var( 'paged' ) ) ) {
				$description = wc_format_content( term_description() );
				if ( $description ) {
					echo '<div class="term-description">' . $description . '<p><a href="#description-continued">' . $anchor_link . '</a></p>' . '</div>';
				}
			}
		}
	}


// th-- Unhook taxonomy title and description (Genesis), then hook in title
remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
add_action( 'genesis_before_loop', 'th_genesis_do_taxonomy_title_only', 15 );
/**
 * Add custom heading and / or description to category / tag / taxonomy archive pages.
 *
 * If the page is not a category, tag or taxonomy term archive, or there's no term, or
 * no term meta set, then nothing extra is displayed.
 *
 * If there's a title to display, it is marked up as a level 1 heading.
 *
 * If there's a description to display, it runs through `wpautop()` before being added to a div.
 *
 * @since 1.3.0
 *
 * @global WP_Query $wp_query Query object.
 *
 * @return void Return early if not the correct archive page, or no term is found.
 */
function th_genesis_do_taxonomy_title_only() {

	global $wp_query;

	if ( ! is_category() && ! is_tag() && ! is_tax() ) {
		return;
	}
//  added
//  && !is_tax( 'product_cat' ) && !is_tax( 'product_tag' )
//  to get taxonomy and term from queried_object as opposed to get_query_var( 'term' ), which may grab other vars like pa_departments...
	$term = is_tax() && !is_tax( 'product_cat' ) && !is_tax( 'product_tag' ) ? get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ) : $wp_query->get_queried_object();

	if ( ! $term ) {
		return;
	}

	$term_link = get_term_link( $term );

	$headline = get_term_meta( $term->term_id, 'headline', true );
	if ( empty( $headline ) && genesis_a11y( 'headings' ) && ( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) ) {
		$heading = '<a href="' . $term_link . '" >' . $term->name . '</a>';
	} else {
		$heading = $term->name;
	}

	$intro_text = '';

	/**
	 * Archive headings output hook.
	 *
	 * Allows you to reorganize output of the archive headings.
	 *
	 * @since 2.5.0
	 *
	 * @param string $heading    Archive heading.
	 * @param string $intro_text Archive intro text.
	 * @param string $context    Context.
	 */
	do_action( 'genesis_archive_title_descriptions', $heading, $intro_text, 'taxonomy-archive-description' );

}

//  Unhook originnal genesis_do_archive_headings_headline() and hook th_do_archive..., now with a link to reload page without filters
remove_action( 'genesis_archive_title_descriptions', 'genesis_do_archive_headings_headline', 10, 3 );
add_action( 'genesis_archive_title_descriptions', 'th_do_archive_headings_headline', 10, 3 );
/**
 * Add headline for archive headings to archive pages.
 *
 * @since 2.5.0
 *
 * @param string $heading    Optional. Archive heading, default is empty string.
 * @param string $intro_text Optional. Archive intro text, default is empty string.
 * @param string $context    Optional. Archive context, default is empty string.
 */
function th_do_archive_headings_headline( $heading = '', $intro_text = '', $context = '' ) {

	if ( $context && $heading ) {
//		printf( '<h1 %s>%s</h1>', genesis_attr( 'archive-title' ), strip_tags( $heading ) );
		printf( '<h1 %s>%s</h1>', genesis_attr( 'archive-title' ), strip_tags( $heading, '<a>' ) );
//		printf( '<h1 %s>%s</h1>', genesis_attr( 'archive-title' ), $heading );
	}

}



// th-- Hook in taxonomy description (Genesis)
add_action( 'genesis_after_loop', 'th_genesis_do_taxonomy_description_only', 15 );
/**
 * Add custom heading and / or description to category / tag / taxonomy archive pages.
 *
 * If the page is not a category, tag or taxonomy term archive, or there's no term, or
 * no term meta set, then nothing extra is displayed.
 *
 * If there's a title to display, it is marked up as a level 1 heading.
 *
 * If there's a description to display, it runs through `wpautop()` before being added to a div.
 *
 * @since 1.3.0
 *
 * @global WP_Query $wp_query Query object.
 *
 * @return void Return early if not the correct archive page, or no term is found.
 */
function th_genesis_do_taxonomy_description_only() {

	global $wp_query;

	if ( ! is_category() && ! is_tag() && ! is_tax() ) {
		return;
	}

	$term = is_tax() ? get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ) : $wp_query->get_queried_object();

	if ( ! $term ) {
		return;
	}

	$heading = '';

	$description_continued = get_term_meta( $term->term_id, 'intro_text', true );

	if( $description_continued ) {
		$intro_text = '<b id="description-continued" ></b>' . $description_continued;
	} else {
		$intro_text = '';
	}

	$intro_text = apply_filters( 'genesis_term_intro_text_output', $intro_text ? $intro_text : '' );

	/**
	 * Archive headings output hook.
	 *
	 * Allows you to reorganize output of the archive headings.
	 *
	 * @since 2.5.0
	 *
	 * @param string $heading    Archive heading.
	 * @param string $intro_text Archive intro text.
	 * @param string $context    Context.
	 */
	do_action( 'genesis_archive_title_descriptions', $heading, $intro_text, 'taxonomy-archive-description' );

}



// Add ACF Repeater fields to SearchWP -- unnecessary if Custom fields for Page is set to "Any"
function th_searchwp_custom_field_keys_like( $keys ) {
  $keys[] = 'acf_field_name_%'; // will match any Custom Field starting with acf_field_name_
  return $keys;
}
add_filter( 'searchwp_custom_field_keys', 'th_searchwp_custom_field_keys_like' );



//  add logo to primary nav
add_filter( 'wp_nav_menu_items', 'th_add_logo_to_nav', 10, 2 );
function th_add_logo_to_nav( $menu, $args ) {
	if ( 'secondary' === $args->theme_location ) {
    	$menu = '<li><a href="' . esc_url( home_url( '/'  ) ) . '" class="nav-logo" ><img src="' . get_stylesheet_directory_uri() . '/images/acme-logo-orig-traced.svg" ></a></li>' . $menu;
    }
    return $menu;
}


// Register Documents post type
// add_action( 'init', 'register_cpt_document' );

// function register_cpt_document() {

// 	$labels = array(
// 		'name' => __( 'Documents', 'document' ),
// 		'singular_name' => __( 'Document', 'document' ),
// 		'add_new' => __( 'Add new', 'document' ),
// 		'add_new_item' => __( 'Add New Document', 'document' ),
// 		'edit_item' => __( 'Edit Document', 'document' ),
// 		'new_item' => __( 'New Document', 'document' ),
// 		'view_item' => __( 'View Document', 'document' ),
// 		'search_items' => __( 'Search Documents', 'document' ),
// 		'not_found' => __( 'No documents found', 'document' ),
// 		'not_found_in_trash' => __( 'No documents found in Trash', 'document' ),
// 		'parent_item_colon' => __( 'Parent Document:', 'document' ),
// 		'menu_name' => __( 'Documents', 'document' ),
// 	);

// 	$args = array(
// 		'labels' => $labels,
// 		'hierarchical' => false,
// 		'description' => 'This custom post type holds support documents.',
// 		'supports' => array( 'page-attributes', 'editor', 'title', 'genesis-seo', 'thumbnail','genesis-cpt-archives-settings' ),
// 		'taxonomies' => array( 'Document type' ),
// 		'public' => true,
// 		'show_ui' => true,
// 		'show_in_menu' => true,
// 		'menu_position' => 5,
// 		'show_in_nav_menus' => false,
// 		'publicly_queryable' => true,
// 		'exclude_from_search' => false,
// 		'has_archive' => true,
// 		'query_var' => true,
// 		'can_export' => true,
// 		'rewrite' => true,
// 		'capability_type' => 'page'
// 	);

// 	register_post_type( 'document', $args );
// }

