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
 *  3. Hook widget output on page: th_add_fx_gallery_widgets()
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
	if ( is_page( 'special-effects') )
	genesis_widget_area( 'fx-gallery-widget-area', array(
        'before' => '<div class="all-fx-widgets">',
        'after' => '</div>',
	) );
}



//  =======================
//  = wrap .hs-table tables in divs, for horizontal scroll
//  =======================

function th_wrap_table( $content ) {
   $pattern = '/(<table class=\"hs-table.*?<\/table>)/is';
   $replacement = '<div class="horizontal-scroll-table">$1</div>';
   $content = preg_replace( $pattern, $replacement, $content );
   return $content;
}
add_filter( 'the_content', 'th_wrap_table', 600 );




