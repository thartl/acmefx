<?php
/**
 * Acme FX.
 *
 * This file adds functions to the Acme FX theme.
 * This is overflow from functions.php and functions-extended.php
 *
 * @package Acme FX
 * @author  Parkdale Wire
 * @license GPL-2.0+
 * @link    http://www.studiopress.com/
 * @link    http://www.parkdalewire.com/
 */


/*
 *  To create more widgets:
 *  1. Comment out "Hide ACF Menu" and "ACF Widgets -- Enable LITE MODE" to bring back admin menus; OR conditionally turn on for some users...
 *  2. Add widget areas (displayed in admin): genesis_register_widget_area()
 *  3. Hook widget output on page: e.g. th_add_fx_gallery_widgets()
 *  ====  IN ADMIN  ====
 *  4. Appearance >> Add New Widgets; Add Widget
 *  5. Back to "Add New Widgets" page; copy theme template file name
 *  6. Create template in child theme folder (use file name from previous step)
 *  7. Modify template to display custom fields etc.
**/



/**
 *   Hide ACF menu, except for user: tomas-acme-dev-admin
 */
add_filter( 'acf/settings/show_admin', 'th_acf_hide_for_most' );

function th_acf_hide_for_most( $content ) {

$current_user = wp_get_current_user();
$current_username = $current_user->user_login;

	if ( $current_username == 'tomas-acme-dev-admin' ) {
		return $content;
	} else {
		return false;
	}
}


/**
 *   ACF Widgets -- Enable LITE MODE, except for user: tomas-acme-dev-admin (if not deactivated)
 */
add_filter( 'acfw_lite', 'th_acf_widgets_hide_for_most' ); // hides all admin screens but the plugin stays active if installed. Similar to ACF hide.

function th_acf_widgets_hide_for_most( $content ) {

$current_user = wp_get_current_user();
$current_username = $current_user->user_login;

	if ( $current_username == 'tomas-acme-dev-admin' && 1 == 1 ) {  // User deactivated ( 1 == 2 )
		return $content;
	} else {
		return true;
	}
}



//  Add widget areas: FX Gallery Widget, 
genesis_register_widget_area( array(
	'id'		=> 'fx-gallery-widget-area',
	'name'		=> __( 'FX Gallery widget area', 'genesis-child' ),
	'description'	=> __( 'This widget area appears on the FX Gallery page, below regular content.', 'genesis-child' ),
) );


//  Display FX Gallery wigets on the FX Gallery page
add_action( 'genesis_after_entry_content', 'th_add_fx_gallery_widgets' );
function th_add_fx_gallery_widgets() {
	if ( is_page( array( 'special-effects' ) ) )
	genesis_widget_area( 'fx-gallery-widget-area', array(
        'before' => '<div class="all-fx-widgets">',
        'after' => '</div>',
	) );
}



//  =======================
//  = wrap .hs-table tables in divs, for horizontal scroll
//  =======================

function th_wrap_table( $content ) {
	// if( preg_match(pattern, subject) )  //  to develop... catch style:min-width and apply to notice and wrap
	  //  NO, instead detect if table has maxWidth in js, and if so apply to wrap
   $pattern = '/(<table class=\"hs-table.*?<\/table>)/is';
   $replacement = '<div class="scroll-notice"><span class="dashicons dashicons-arrow-left-alt"></span> table scrolls <span class="dashicons dashicons-arrow-right-alt"></span></div><div class="hs-wrap">$1</div>';
   $content = preg_replace( $pattern, $replacement, $content );
   return $content;
}
add_filter( 'the_content', 'th_wrap_table', 600 );



/** Display scroll notice when table becomes scrollable **********************************************************************/
add_action( 'wp_footer', 'th_table_scroll_notice', 100 );

	function th_table_scroll_notice() {

			?><script type="text/javascript">

				jQuery( document ).ready(function( $ ) {

					$( '.hs-wrap' ).each( function( index ) {

						var table_max_width = parseFloat( $( '.hs-table', this ).css( 'maxWidth' ) );
						if( table_max_width > 20 ) {
							$(this).css( 'maxWidth', table_max_width );
						}

						var wrap_w = $(this).width();
						var table_w = $( '.hs-table', this ).width();

						if( ( wrap_w - table_w + 20 ) < 0 && !$(this).hasClass( 'scroll-enabled' ) ) {

							$(this).addClass( 'scroll-enabled' );
							$(this).prev( '.scroll-notice' ).delay(660).slideDown();

						}

					});  //  END .each


					// Listen for resize changes
					window.addEventListener("resize", function() {

						$( '.hs-wrap' ).each( function( index ) {

							var wrap_w_new = $(this).width();
							var table_w_new = $( '.hs-table', this ).width();

							if( (wrap_w_new - table_w_new) + 20 < 0 && !$(this).hasClass( 'scroll-enabled' ) )  {
								$(this).addClass( 'scroll-enabled' );
								$(this).prev( '.scroll-notice' ).slideDown( 400 );
							}

							// Wrap is always bigger by twice the width of border => "+ 5" to account for that
							if( (wrap_w_new - table_w_new) + 5 > 0 && $(this).hasClass( 'scroll-enabled' ) )  {
								$(this).removeClass( 'scroll-enabled' );
								$(this).prev( '.scroll-notice' ).hide( '300' );
							}

						});

					}, true );

				});  //  END jQuery

			</script><?php

	}

/** END: Display scroll notice when table becomes scrollable  **********************************************************************/


















