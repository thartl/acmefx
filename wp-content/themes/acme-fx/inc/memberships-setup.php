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
 * After My Account (Dashboard) content: add a link to Membeships section (does not test for membership plan ID, hence lists memberships even if there is only one)
 *
 */

add_action( 'woocommerce_account_dashboard', 'th_link_to_memberships_section' );

function th_link_to_memberships_section() {

	$my_account_url = wc_get_page_permalink( 'myaccount' );
	$memberships_endpoint = get_option( 'woocommerce_myaccount_members_area_endpoint', 'members-area' );
	$members_area_url = $my_account_url . $memberships_endpoint . '/';

	echo '<p>You may also <a href="'. esc_url( $members_area_url ) . '"	>see your active memberships or request a new membership</a>.</p>';

}



/**
 * Add a button to dispaly a form -- used to request Library Membership. Doesn't display if user already has Library Membership.
 * Connect to form --> $form_id
 *
 */

add_action( 'wc_memberships_after_my_memberships', 'th_add_membership_request_form' );

function th_add_membership_request_form() {

	$current_user = wp_get_current_user();
	$user_email = $current_user->user_email;
    $user_first_name = $current_user->user_firstname;
    $user_last_name = $current_user->user_lastname;

    /** Set up form attributes */
		$attributes = array(
		'title'        => true,
		'description'  => false,
		'name'         => '',
		'field_values' => array(
			'membership_request_first_name' => $user_first_name,
			'membership_request_last_name' => $user_last_name,
			'membership_request_email' => $user_email,			
			),
		'tabindex'     => 1,
	);

	/** Construct the form */
	$form_id = 2;  /** Which form ID? */
	$text    = 'Apply for Library Membership';
	$onclick = "jQuery('#gravityform_button_{$form_id}, #gravityform_container_{$form_id}').slideToggle();";
	$html  = sprintf( '<button id="gravityform_button_%1$d" class="gravity_button" onclick="%2$s">%3$s</button>', esc_attr( $form_id ), $onclick, esc_attr( $text ) );
	$html .= sprintf( '<div id="gravityform_container_%1$d" class="gravity_container" style="display:none;">', esc_attr( $form_id ) );
	$html .= gravity_form( $form_id, $attributes['title'], $attributes['description'], false, $attributes['field_values'], true, $attributes['tabindex'], false );
	$html .= '</div>';

	/** Needed to enqueque styles and scripts. Scripts are necessary for forms that contain conditional logic or the date picker field. */
	gravity_form_enqueue_scripts( $form_id, true );

	echo $html;

}


