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


/** Hover on Touch *
 *	Source:  http://jsfiddle.net/c_kick/s9rB4/
 *********************************************************/
// add_action( 'wp_footer', 'th_hover_on_touch', 200 );

// function th_hover_on_touch() {

// 	?><script type="text/javascript">

// 		jQuery( function ( $ ) {

// 			$('a.taphover').on("touchstart", function (e) {
// 			    "use strict";
// 			    var link = $(this);
// 			    if (link.hasClass('hover')) {
// 			        return true;
// 			    } else {
// 			        link.addClass("hover");
// 			        $('a.taphover').not(this).removeClass("hover");
// 			        e.preventDefault();
// 			        return false;
// 			    }
// 			});

// 		});

// 	</script><?php

// }


add_action( 'wp_footer', 'th_hover_on_touch', 200 );

function th_hover_on_touch() {

	?><script type="text/javascript">

		jQuery( function ( $ ) {

			$('a.taphover').on("touchstart", function (e) {
			    "use strict";
			    var link = $(this);
			    if (link.hasClass('hover') && link.hasClass( 'hover-cont' )) {
			        return true;
			    } else {
			        link.addClass("hover");
			        $('a.taphover').not(this).removeClass("hover");
			        e.preventDefault();
			        return false;
			    }
			});

			$('a.taphover').on("touchend", function (e) {
			    "use strict";
			    var link = $(this);
			    if (link.hasClass('hover-cont')) {
			        return true;
			    } else {
			        link.addClass("hover-cont");
			        $('a.taphover').not(this).removeClass("hover-cont");
			        e.preventDefault();
			        return false;
			    }
			});

		});

	</script><?php

}

/*********************************************************************************************************************************/
/************************ th-- WC Grid/List View *********************************************************************************/

/** Add options page for store view constants */
if( function_exists( 'acf_add_options_page' ) ) {

	acf_add_options_page( array(
		'page_title' => 'Site Options - Custom settings for Acme FX',
		'menu_title' => 'Site Options',
		'menu_slug' => 'acme-customizer',
		'capability' => 'edit_posts',
		'redirect' => false,
		'position' => '80.129'
	));

}



// Set the number of products per page for Grid, List, and Description Views
add_filter( 'loop_shop_per_page', 'th_list_view_loop_shop_per_page', 22 );
function th_list_view_loop_shop_per_page( $cols ) {

	$grid_view_products_per_page = esc_html( get_option( 'options_grid_view_products_per_page' ) );
	$list_view_products_per_page = esc_html( get_option( 'options_list_view_products_per_page' ) );
	$desc_view_products_per_page = esc_html( get_option( 'options_desc_view_products_per_page' ) );


	if(!isset($_COOKIE['store_view']) || $_COOKIE['store_view'] == 'grid') {
		$cols = $grid_view_products_per_page;
		return $cols;
	} elseif ( $_COOKIE['store_view'] == 'list' ) {
		$cols = $list_view_products_per_page;
		return $cols;
	} elseif ( $_COOKIE['store_view'] == 'desc' ) {
		$cols = $desc_view_products_per_page;
		return $cols;
	} else {
		return $cols;
	}
}


/************************ Place Grid/List View buttons ***************************************************************************/
add_action( 'woocommerce_before_shop_loop', 'th_grid_list_buttons', 18 );
function th_grid_list_buttons() {

	if( !isset($_COOKIE['store_view']) || $_COOKIE['store_view'] == 'grid' ) {

		echo '<div class="grid-list-switch grid-view">';

	} elseif( $_COOKIE['store_view'] == 'list' ) {

		echo '<div class="grid-list-switch list-view">';

	} else {

		echo '<div class="grid-list-switch desc-view">';

	}

		echo '<div class="grid-view-btn" ><svg fill="#fff" class="grid-view-btn" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" x="0px" y="0px"><title>Grid view</title><path d="M501,249v10a2,2,0,0,1-2,2H489a2,2,0,0,1-2-2V249a2,2,0,0,1,2-2h10A2,2,0,0,1,501,249Zm16-2H507a2,2,0,0,0-2,2v10a2,2,0,0,0,2,2h10a2,2,0,0,0,2-2V249A2,2,0,0,0,517,247Zm18,0H525a2,2,0,0,0-2,2v10a2,2,0,0,0,2,2h10a2,2,0,0,0,2-2V249A2,2,0,0,0,535,247Zm-36,18H489a2,2,0,0,0-2,2v10a2,2,0,0,0,2,2h10a2,2,0,0,0,2-2V267A2,2,0,0,0,499,265Zm18,0H507a2,2,0,0,0-2,2v10a2,2,0,0,0,2,2h10a2,2,0,0,0,2-2V267A2,2,0,0,0,517,265Zm18,0H525a2,2,0,0,0-2,2v10a2,2,0,0,0,2,2h10a2,2,0,0,0,2-2V267A2,2,0,0,0,535,265Zm-36,18H489a2,2,0,0,0-2,2v10a2,2,0,0,0,2,2h10a2,2,0,0,0,2-2V285A2,2,0,0,0,499,283Zm18,0H507a2,2,0,0,0-2,2v10a2,2,0,0,0,2,2h10a2,2,0,0,0,2-2V285A2,2,0,0,0,517,283Zm18,0H525a2,2,0,0,0-2,2v10a2,2,0,0,0,2,2h10a2,2,0,0,0,2-2V285A2,2,0,0,0,535,283Z" transform="translate(-487 -247)"></path></svg></div>' .

			'<div class="desc-view-btn" ><?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE svg  PUBLIC "-//W3C//DTD SVG 1.1//EN"  "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg enable-background="new 0 0 512 512" fill="#fff" class="desc-view-btn" version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><title>Description view</title>
		<path d="m492.17 0.033v-0.699h-287.67v0.681c-11.171 0.162-20.181 9.255-20.181 20.465v102.4c0 11.21 9.01 20.303 20.181 20.465v0.155h287.67v-0.173c11.009-0.344 19.833-9.354 19.833-20.447v-102.4c0-11.093-8.824-20.103-19.833-20.447z"/>
		<path d="m492.17 186.02v-0.699h-287.67v0.681c-11.171 0.162-20.181 9.255-20.181 20.465v102.4c0 11.21 9.01 20.304 20.181 20.465v0.155h287.67v-0.173c11.009-0.344 19.833-9.354 19.833-20.447v-102.4c0-11.093-8.824-20.103-19.833-20.447z"/>
		<path d="m492.17 368.53v-0.699h-287.67v0.682c-11.171 0.161-20.181 9.254-20.181 20.465v102.4c0 11.21 9.01 20.304 20.181 20.465v0.155h287.67v-0.173c11.009-0.345 19.833-9.354 19.833-20.447v-102.4c0-11.093-8.824-20.103-19.833-20.447z"/>
			<path d="m122.88 368.64h-102.4c-11.311 0-20.48 9.169-20.48 20.48v102.4c0 11.311 9.169 20.48 20.48 20.48h102.4c11.311 0 20.48-9.169 20.48-20.48v-102.4c0-11.311-9.169-20.48-20.48-20.48z"/>
			<path d="M122.88,0H20.48C9.169,0,0,9.169,0,20.48v102.4c0,11.311,9.169,20.48,20.48,20.48h102.4     c11.311,0,20.48-9.169,20.48-20.48V20.48C143.36,9.169,134.191,0,122.88,0z"/>
			<path d="m122.88 184.32h-102.4c-11.311 0-20.48 9.169-20.48 20.48v102.4c0 11.311 9.169 20.479 20.48 20.479h102.4c11.311 0 20.48-9.169 20.48-20.479v-102.4c0-11.311-9.169-20.48-20.48-20.48z"/>
</svg></div>' .

			'<div class="list-view-btn" ><svg fill="#fff" class="list-view-btn" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><title>List view</title><path d="M4,0h92c2.209,0,4,1.791,4,4v12c0,2.209-1.791,4-4,4H4c-2.209,0-4-1.791-4-4V4C0,1.791,1.791,0,4,0z"></path><path d="M4,26.667h92c2.209,0,4,1.791,4,4v12c0,2.209-1.791,4-4,4H4c-2.209,0-4-1.791-4-4v-12
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


/** Set cookies and reload page  **********************************************************************/
add_action( 'wp_footer', 'th_grid_list_switches', 100 );

	function th_grid_list_switches() {

	$grid_view_products_per_page = esc_html( get_option( 'options_grid_view_products_per_page' ) );
	$list_view_products_per_page = esc_html( get_option( 'options_list_view_products_per_page' ) );
	$desc_view_products_per_page = esc_html( get_option( 'options_desc_view_products_per_page' ) );


		if( is_shop() || ( is_woocommerce() && is_archive() ) ) {		

			?><script type="text/javascript">

				jQuery(function( $ ) {

					var store_view = Cookies.get( 'store_view' );  // which cookie is set?

					var grid_ppp = <?php echo $grid_view_products_per_page; ?>;  // how many products per page for each view?
					var list_ppp = <?php echo $list_view_products_per_page; ?>;
					var desc_ppp = <?php echo $desc_view_products_per_page; ?>;

					var current_view_ppp = grid_ppp;  // default "products per page" is grid
					if( store_view == 'list' ) {  // if grid not set "products per page" to one of the other views
						current_view_ppp = list_ppp;
					} else if( store_view == 'desc' ) {
						current_view_ppp = desc_ppp;
					}

					var url_all = window.location.href;  // a straight reload would do this
					var url_pageless = url_all.replace( /\/page\/[0-9]+\// , '/' );  // remove page variable

					var pagination_array = url_all.match( /\/page\/[0-9]+\// );  // gives array e.g. "[ '/page/2/' ]" or null

					var page_or_not = 'pagination-is-on'; //  either left alone (this means construct a new paginated url) OR overwritten by url without pagination (then use that)

					if( Array.isArray( pagination_array ) ) {  // if there is pagination extract it

						var pagination_string = pagination_array[0];  // gives e.g. "/page/2/"

						var page_number_interim = pagination_string.replace( /\/page\// , '' );  // removes "/page/"
						var page_number_string = page_number_interim.replace( /\// , '' );  // removes "/" before page number
						var current_page_number = parseInt( page_number_string, 10 );  // string to int

						var focus_product_number = ( ( current_page_number - 1 ) * current_view_ppp ) + 1;

					} else {

						page_or_not = url_all.replace( /\/page\/[0-9]+\// , '/' );  // we're on page 1, replace with a "/" only

					}

					var new_page_number;
					var pagination;
					var new_url;

			// console.log( 'window.location.href: ' + url_all );
			// console.log( 'Current view ppp: ' + current_view_ppp );
			// console.log( 'Current focus product number: ' + focus_product_number );

						$( "div.grid-view-btn" ).click( function() {
							if( store_view !== 'grid' ) {
								Cookies.set( 'store_view', 'grid', { expires: 30 } );
								if( page_or_not == 'pagination-is-on' ) {  // either paginate or go to page 1 (no pagination)
									new_page_number = Math.ceil( focus_product_number / grid_ppp );

									if( new_page_number == 1 ) {
										window.location.href = url_pageless;
									} else {
										pagination = '/page/' + new_page_number + '/';
										new_url = url_all.replace( /\/page\/[0-9]+\//, pagination );
										window.location.href = new_url;
									}

								} else {
									window.location.href = page_or_not;
								}
							}
						});

						$( "div.list-view-btn" ).click( function() {
							if( store_view !== 'list' ) {
								Cookies.set( 'store_view', 'list', { expires: 30 } );
								if( page_or_not == 'pagination-is-on' ) {  // either paginate or go to page 1 (no pagination)
									new_page_number = Math.ceil( focus_product_number / list_ppp );

									if( new_page_number == 1 ) {
										window.location.href = url_pageless;
									} else {
										pagination = '/page/' + new_page_number + '/';
										new_url = url_all.replace( /\/page\/[0-9]+\//, pagination );
										window.location.href = new_url;
									}

								} else {
									window.location.href = page_or_not;
								}
							}
						});

						$( "div.desc-view-btn" ).click( function() {
							if( store_view !== 'desc' ) {
								Cookies.set( 'store_view', 'desc', { expires: 30 } );
								if( page_or_not == 'pagination-is-on' ) {  // either paginate or go to page 1 (no pagination)
									new_page_number = Math.ceil( focus_product_number / desc_ppp );

									if( new_page_number == 1 ) {
										window.location.href = url_pageless;
									} else {
										pagination = '/page/' + new_page_number + '/';
										new_url = url_all.replace( /\/page\/[0-9]+\//, pagination );
										window.location.href = new_url;
									}

								} else {
									window.location.href = page_or_not;
								}
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

// if( !isset( $_COOKIE['store_view'] ) ) {
// 	  echo '<br>The cookie: store_view is NOT set.';
// 	} else {
// 	  echo '<br>Cookie is:  <h3>' . $_COOKIE['store_view'] . '</h3><br>';
// 	}
// }
/*************************************************************************/



/*** Bring Short product description to Description view *******************************************************/
add_action( 'genesis_before_loop', 'th_add_short_description_to_desc_view' );
function th_add_short_description_to_desc_view() {

	if( !isset($_COOKIE['store_view']) || $_COOKIE['store_view'] == 'grid' || $_COOKIE['store_view'] == 'list'  ) {

		return;

	} elseif ( $_COOKIE['store_view'] == 'desc' ) {

		add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_single_excerpt', 12 );

	}

}


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
				$anchor_link = '<a href="#description-continued" class="cat-tag-continue-reading" >Read more about ' . $term_name . '</a>';
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

		printf( '<h1 %s>%s</h1>', genesis_attr( 'archive-title' ), strip_tags( $heading, '<a>' ) );

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

//  added
//  && !is_tax( 'product_cat' ) && !is_tax( 'product_tag' )
//  to get taxonomy and term from queried_object as opposed to get_query_var( 'term' ), which may grab other vars like pa_departments...
	$term = is_tax() && !is_tax( 'product_cat' ) && !is_tax( 'product_tag' ) ? get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ) : $wp_query->get_queried_object();

	if ( ! $term ) {
		return;
	}

	$heading = '';

	$description_continued = get_term_meta( $term->term_id, 'intro_text', true );

	if( $description_continued && !is_paged() ) {
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

/** Get current URL ****************************************************************/
/** Source: https://gist.github.com/leereamsnyder/fac3b9ccb6b99ab14f36 ************
 * Re-use some existing WordPress functions so
 * you don't have to write a bunch of raw PHP to check for SSL, port numbers, etc
 * Place in your functions.php (or re-use in a plugin)
 * If you absolutely don't need or want any query string, use home_url(add_query_arg(array(),$wp->request));
 *
 * Hat tip to:
 *  + http://kovshenin.com/2012/current-url-in-wordpress/
 *  + http://stephenharris.info/how-to-get-the-current-url-in-wordpress/
 *********************************************************************************/
/**
* Build the entire current page URL (incl query strings) and output it
* Useful for social media plugins and other times you need the full page URL
* Also can be used outside The Loop, unlike the_permalink
* 
* @returns the URL in PHP (so echo it if it must be output in the template)
* Also see the_current_page_url() syntax that echoes it
*/


/** th-- Added trailingslashit() to add "/" before query vars **/

if ( ! function_exists( 'get_current_page_url' ) ) {

	function get_current_page_url() {

		global $wp;

		return add_query_arg( $_SERVER['QUERY_STRING'], '', trailingslashit( home_url( $wp->request ) ) );

	}

}

	/*
	* Shorthand for echo get_current_page_url(); 
	* @returns echo'd string
	*/

	if ( ! function_exists( 'the_current_page_url' ) ) {

		function the_current_page_url() {

		echo get_current_page_url();

		}
	}

/** th-- END: Get current URL **/



