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
 * Connect to form in 2 places:
 * 1) in the first add_action --> last character of the hook 'gform_after_submission_'
 * 2) in function th_add_membership_request_form() --> $form_id
 *
 */

/** 
 * Make sure user metadata get updated when form is submitted
 * Last character of this hook is Gravity Forms ID *************************************************/
add_action( 'gform_after_submission_3', 'th_update_user_meta', 10, 2 );
function th_update_user_meta( $entry, $form ) {

    $first_name = rgar( $entry, '1.3' );
    $last_name = rgar( $entry, '1.6' );
    $email = rgar( $entry, '2' );

	$user_id = get_current_user_id();

	wp_update_user( array(
		'ID' => $user_id,
		'first_name' => $first_name,
		'last_name' => $last_name,
		'user_email' => $email
		) );
		 
}

/** Get existing user data, construct form, make prefilled data fields inactive */
/** $form_id refers to Gravity Forms ID */
add_action( 'wc_memberships_after_my_memberships', 'th_add_membership_request_form' );

function th_add_membership_request_form() {

	$form_id = 3;  /** Which form ID? */

	/** Get user data */
	$current_user = wp_get_current_user();
	$user_ID = $current_user->ID;
	$user_email = $current_user->user_email;
    $user_first_name = $current_user->user_firstname;
    $user_last_name = $current_user->user_lastname;


    /** Set up form attributes */
		$attributes = array(
		'title'        => true,
		'description'  => true,
		'name'         => '',
		'field_values' => array(
			'membership_request_first_name' => $user_first_name,
			'membership_request_last_name' => $user_last_name,
			'membership_request_email' => $user_email,			
			),
		'tabindex'     => 1,
	);


	/** Make prefilled fields read only */
	$gform_pre_render_hook = 'gform_pre_render_' . $form_id;

	if( $user_email ) {
		add_filter( $gform_pre_render_hook, 'th_add_readonly_email' );
		function th_add_readonly_email( $form ) {
			?>  		<script type="text/javascript">
				        	jQuery(document).ready(function(){
				        	jQuery("li.gf_email_maybe_readonly input").attr("readonly","readonly");
			        	});
			    		</script>
	    	<?php	return $form;
	    	}
	    }

	if( $user_first_name ) {
		add_filter( $gform_pre_render_hook, 'th_add_readonly_first_name' );
		function th_add_readonly_first_name( $form ) {
			?>  		<script type="text/javascript">
				        	jQuery(document).ready(function(){
				        	jQuery("li.gf_name_maybe_readonly .name_first input").attr("readonly","readonly");
			        	});
			    		</script>
	    	<?php	return $form;
	    	}
	    }

	if( $user_last_name ) {
		add_filter( $gform_pre_render_hook, 'th_add_readonly_last_name' );
		function th_add_readonly_last_name( $form ) {
			?>  		<script type="text/javascript">
				        	jQuery(document).ready(function(){
				        	jQuery("li.gf_name_maybe_readonly .name_last input").attr("readonly","readonly");
			        	});
			    		</script>
	    	<?php	return $form;
	    	}
	    }


	/** Construct the form */
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


