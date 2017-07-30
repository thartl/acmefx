<?php
/**
 * Acme FX.
 *
 * This file adds the front page template to the Acme FX theme.
 * 
 *
 * @package Acme FX
 * @author  Parkdale Wire
 * @license GPL-2.0+
 * @link    http://parkdalewire.com
 */



// Function to initiate widgetized page render.
add_action( 'genesis_meta', 'acme_front_page_init' );
function acme_front_page_init() {

	if ( is_active_sidebar( 'front-page-1' ) || is_active_sidebar( 'front-page-2' ) || is_active_sidebar( 'front-page-3-a' ) || is_active_sidebar( 'front-page-3-b' ) || is_active_sidebar( 'front-page-4' ) || is_active_sidebar( 'front-page-5' ) ) {

		// Add front-page body class.
		add_filter( 'body_class', 'acme_body_class' );

		// Force full width.
		add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

		// Remove default loop.
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		// Add the widget areas.
		add_action( 'genesis_loop', 'acme_home_widget_loop' );

		// Modify the read more link.
		add_filter( 'get_the_content_limit', 'spi_content_limit_read_more_markup', 10, 3 );
	}
}


// Front page body class.
function acme_body_class( $classes ) {

	$classes[] = 'front-page';

	return $classes;
}


// Function to output active widget areas.
function acme_home_widget_loop() {

	echo '<h2 class="screen-reader-text">' . __( 'Main Content', 'genesis-sample' ) . '</h2>';

	genesis_widget_area( 'front-page-1', array(
		'before' => '<div class="flexible-widgets front-page-1 color' . acme_widget_area_class( 'front-page-1' ) . ' widget-area"><div class="wrap">',
		'after'  => '</div></div>',
	) );

	genesis_widget_area( 'front-page-2', array(
		'before' => '<div class="flexible-widgets front-page-2 image' . acme_widget_area_class( 'front-page-2' ) . ' widget-area"><div class="wrap">',
		'after'  => '</div></div>',
	) );

	genesis_widget_area( 'front-page-3-a', array(
		'before' => '<div class="flexible-widgets front-page-3 front-page-3-a color' . acme_widget_area_class( 'front-page-3-a' ) . ' widget-area"><div class="wrap">',
		'after'  => '</div></div>',
	) );

	genesis_widget_area( 'front-page-3-b', array(
		'before' => '<div class="flexible-widgets front-page-3 front-page-3-b color' . acme_widget_area_class( 'front-page-3-b' ) . ' widget-area"><div class="wrap">',
		'after'  => '</div></div>',
	) );

	genesis_widget_area( 'front-page-4', array(
		'before' => '<div class="flexible-widgets front-page-4' . acme_widget_area_class( 'front-page-4' ) . ' widget-area"><div class="wrap">',
		'after'  => '</div></div>',
	) );

	genesis_widget_area( 'front-page-5', array(
		'before' => '<div class="flexible-widgets front-page-5' . acme_widget_area_class( 'front-page-5' ) . ' widget-area"><div class="wrap">',
		'after'  => '</div></div>',
	) );

}

// Modify the read more link.  -- hooked by acme_front_page_init()
function spi_content_limit_read_more_markup( $output, $content, $link ) {

	$output = sprintf( '<p>%s &#x02026;</p>%s', $content, str_replace( '&#x02026;', '', $link ) );
	return $output;

}
genesis();