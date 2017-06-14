<?php
/**
 * Acme FX.
 *
 * This file adds functions to the Acme FX Theme.
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



// Change the number of products per page
//add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {
  // $cols contains the current number of products per page based on the value stored on Options -> Reading
  // Return the number of products you wanna show per page.
  $cols = 3;
  return $cols;
}

//  Add link to bottom of page if text contiues
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description' );
add_action( 'woocommerce_archive_description', 'th_woocommerce_taxonomy_archive_description' );
	function th_woocommerce_taxonomy_archive_description() {
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

	$term = is_tax() ? get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ) : $wp_query->get_queried_object();

	if ( ! $term ) {
		return;
	}

	$heading = get_term_meta( $term->term_id, 'headline', true );
	if ( empty( $heading ) && genesis_a11y( 'headings' ) ) {
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

	$intro_text = '<b id="description-continued" ></b>' . get_term_meta( $term->term_id, 'intro_text', true );
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

/**
 * Plugin Name: Disable ACF on Frontend
 * Description: Provides a performance boost if ACF frontend functions aren't being used
 * Version:     1.0
 * Author:      Bill Erickson
 * Author URI:  http://www.billerickson.net
 * License:     MIT
 * License URI: http://www.opensource.org/licenses/mit-license.php
 */
 
/**
 * Disable ACF on Frontend
 *
 */
function ea_disable_acf_on_frontend( $plugins ) {
	if( is_admin() )
		return $plugins;
	foreach( $plugins as $i => $plugin )
		if( 'advanced-custom-fields-pro/acf.php' == $plugin )
			unset( $plugins[$i] );
	return $plugins;
}
add_filter( 'option_active_plugins', 'ea_disable_acf_on_frontend' );


