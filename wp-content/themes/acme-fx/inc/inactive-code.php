

<?php
/** REMOVE OPENING PHP TAG IF MOVING BACK TO AN EXISTING PHP FILE *******************************************************/
/** REMOVE OPENING PHP TAG IF MOVING BACK TO AN EXISTING PHP FILE *******************************************************/


/** DEACTIVATED */
/********** Build FedEx / Shipping Class restrictions ******************************************************************************/
add_filter( 'woocommerce_package_rates', 'th_shipping_methods_restricted', 10, 2 );
function th_shipping_methods_restricted( $rates, $package ) {

	$sb_ship_notice = false;

	$shipping_class_ids = array(
		81,
	);

	$shipping_services_to_hide = array(
		'fedex:FEDEX_EXPRESS_SAVER',
		'fedex:FEDEX_GROUND',
		'fedex:FEDEX_2_DAY',
		'fedex:STANDARD_OVERNIGHT',
		'fedex:PRIORITY_OVERNIGHT',
		'fedex:FIRST_OVERNIGHT'
	);

	// $shipping_services_to_hide = array(
	// 	'wf_fedex_woocommerce_shipping:FEDEX_EXPRESS_SAVER',
	// 	'wf_fedex_woocommerce_shipping:FEDEX_GROUND',
	// 	'wf_fedex_woocommerce_shipping:FEDEX_2_DAY',
	// 	'wf_fedex_woocommerce_shipping:STANDARD_OVERNIGHT',
	// 	'wf_fedex_woocommerce_shipping:PRIORITY_OVERNIGHT',
	// 	'wf_fedex_woocommerce_shipping:FIRST_OVERNIGHT'
	// );

	$exclude_areas = array(
		'AB',
		'BC',
		'YT'
	);
	$restricted_shipping_class = false;
	foreach( WC()->cart->cart_contents as $key => $values ) {
		if ( in_array($values['data']->get_shipping_class_id() , $shipping_class_ids ) ) {
			$restricted_shipping_class = true;
			break;
		}
	}
	$shipping_area_restricted = false;
	if ( is_array( $exclude_areas ) ) {
		if ( in_array( WC()->customer->shipping_state, $exclude_areas ) ) {
			$shipping_area_restricted = true;
		}
	}

	if ( $restricted_shipping_class && $shipping_area_restricted ) {

		if ( is_array( $shipping_services_to_hide ) ) {
			// wc_add_notice( __( 'We are sorry, Snow Business products do not ship to BC, AB, and YT.', 'woocommerce' ), 'error' );
			$sb_ship_notice = true;

			foreach( $shipping_services_to_hide as $excluded_province ) {
				unset( $rates[ $excluded_province ] );
			}
		}
	}

	return $rates;
}

/** BOTH HOOKS DEACTIVATED */
/************ Output a notice if Snow Business products cannot be shipped (i.e. to BC, AB, YT) *********************************/
add_action( 'woocommerce_calculated_shipping', 'th_shipping_restriction_notice' );  // Cart page
add_action( 'woocommerce_review_order_after_order_total', 'th_shipping_restriction_notice', 100 );  // Checkout page

function th_shipping_restriction_notice() {
	global $woocommerce;

	$shipping_class_ids = array(
		81,
	);

	$shipping_services_to_hide = array(
		'fedex:FEDEX_EXPRESS_SAVER',
		'fedex:FEDEX_GROUND',
		'fedex:FEDEX_2_DAY',
		'fedex:STANDARD_OVERNIGHT',
		'fedex:PRIORITY_OVERNIGHT',
		'fedex:FIRST_OVERNIGHT'
	);

	// $shipping_services_to_hide = array(
	// 	'wf_fedex_woocommerce_shipping:FEDEX_EXPRESS_SAVER',
	// 	'wf_fedex_woocommerce_shipping:FEDEX_GROUND',
	// 	'wf_fedex_woocommerce_shipping:FEDEX_2_DAY',
	// 	'wf_fedex_woocommerce_shipping:STANDARD_OVERNIGHT',
	// 	'wf_fedex_woocommerce_shipping:PRIORITY_OVERNIGHT',
	// 	'wf_fedex_woocommerce_shipping:FIRST_OVERNIGHT'
	// );

	$exclude_areas = array(
		'AB',
		'BC',
		'YT'
	);
	$restricted_shipping_class = false;
	foreach( WC()->cart->cart_contents as $key => $values ) {
		if ( in_array($values['data']->get_shipping_class_id() , $shipping_class_ids ) ) {
			$restricted_shipping_class = true;
			break;
		}
	}
	$shipping_area_restricted = false;
	if ( is_array( $exclude_areas ) ) {
		if ( in_array( WC()->customer->shipping_state, $exclude_areas ) ) {
			$shipping_area_restricted = true;
		}
	}

	if ( $restricted_shipping_class && $shipping_area_restricted ) {
		if ( is_cart() ) {
			wc_add_notice( __( 'Snow Business products do not ship to BC, AB, or YT.<br>
								Businesses and individuals located in Western Canada are encouraged to speak with <a href="https://hollynorth.com/" target="_blank">Hollynorth Production Supplies</a> (604 299 2000) to order Snow Business products.<br><br>
								To complete this order you may:<br>
								1) Pick up the entire order at our shop<br>
								2) Remove the Snow Business products from your cart and have the other items shipped to you', 'woocommerce' ), 'notice' );
		} else {
			wc_clear_notices();
			wc_add_notice( __( 'Snow Business products do not ship to BC, AB, or YT.<br>
								Businesses and individuals located in Western Canada are encouraged to speak with <a href="https://hollynorth.com/" target="_blank">Hollynorth Production Supplies</a> (604 299 2000) to order Snow Business products.<br><br>
								To complete this order you may:<br>
								1) Pick up the entire order at our shop<br>
								2) Remove the Snow Business products from your cart and have the other items shipped to you<br>
								<p><a class="button wc-backward" href="' . esc_url( wc_get_page_permalink( 'cart' ) ) . '">Return to cart</a></p>', 'woocommerce' ), 'notice' );
		}
	}
}

/** Get shipping class ID (displays only for User  11) -- UNCOMMENT ONLY IN DEV --  *************/
/** Snow Business shipping class ID = 81 *****************************/
//add_action( 'woocommerce_single_product_summary', 'th_print_shipping_class_id' );
function th_print_shipping_class_id() {
	if ( is_product() && get_current_user_id() == 11 ) {

		global $product;
		$shipping_class_id = $product->get_shipping_class_id();

		echo '<p>Shipping class ID of this product is: ' . $shipping_class_id . '</p>';
	}
}


