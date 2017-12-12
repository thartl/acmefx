<?php
/**
 * Acme FX.
 *
 * This file add WooCommerce Memberships modifications.
 *
 * @package Acme FX
 * @author  Parkdale Wire
 * @license GPL-2.0+
 * @link    http://www.studiopress.com/
 * @link    http://www.parkdalewire.com/
 */


/**
 * Add link to Membeships section after My Account content
 *
 */

add_action( 'woocommerce_account_dashboard', 'th_link_to_memberships_section' );

function th_link_to_memberships_section() {

	$my_account_url = wc_get_page_permalink( 'myaccount' );
	$memberships_endpoint = get_option( 'woocommerce_myaccount_members_area_endpoint', 'members-area' );
	$members_area_url = $my_account_url . $memberships_endpoint . '/';

	echo '<p>You may also <a href="'. esc_url( $members_area_url ) . '"	>see your active memberships or request a new membership</a>.</p>';

}

