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
 * 
 *
 */

add_action( 'woocommerce_account_dashboard', 'th_add_test_button' );

function th_add_test_button() {

	?>
		<p>This is a test button.</p>

<?php
}