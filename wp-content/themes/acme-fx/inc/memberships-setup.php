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
 * Used by:
 * th_invite_to_memberships_section() on this page
 *
 */
function th_link_to_memberships_section() {

	$my_account_url = wc_get_page_permalink( 'myaccount' );
	$memberships_endpoint = get_option( 'woocommerce_myaccount_members_area_endpoint', 'members-area' );
	$members_area_url = $my_account_url . $memberships_endpoint . '/';

	return $members_area_url;

}


/**
 * After My Account (Dashboard) content: add a link to Membeships section (does not test for membership plan ID, hence lists memberships even if there is only one)
 *
 */

add_action( 'woocommerce_account_dashboard', 'th_invite_to_memberships_section' );

function th_invite_to_memberships_section() {  /**** Also used in woocommerce/emails/customer-new-account.php ****/

	echo '<p>You may also <a href="'. th_link_to_memberships_section() . '"	>see your active memberships or request a new membership</a>.</p>';

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
// add_action( 'wc_memberships_after_my_memberships', 'th_add_membership_request_form' );

add_filter( 'wc_memberships_my_memberships_no_memberships_text', 'th_add_membership_request_form', 10, 2);

function th_add_membership_request_form( $original_text, $passed_user_id ) {

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
	$onclick = "jQuery('#no-memberships-yet').hide( '400' ); jQuery('#gravityform_button_{$form_id}, #gravityform_container_{$form_id}').slideToggle( '800' );";
	$html = '<p id="no-memberships-yet">Looks like you don\'t have a membership yet!</p>';
	$html .= sprintf( '<button id="gravityform_button_%1$d" class="button apply gravity_button" onclick="%2$s">%3$s</button>', esc_attr( $form_id ), $onclick, esc_attr( $text ) );
	$html .= sprintf( '<div id="gravityform_container_%1$d" class="gravity_container" style="display:none;">', esc_attr( $form_id ) );
	$html .= gravity_form( $form_id, $attributes['title'], $attributes['description'], false, $attributes['field_values'], true, $attributes['tabindex'], false );
	$html .= '</div>';

	/** Needed to enqueque styles and scripts. Scripts are necessary for forms that contain conditional logic or the date picker field. */
	gravity_form_enqueue_scripts( $form_id, true );

	echo $html;

}


/**
 * Customize the restriction message
 *
 */

add_filter( 'wc_memberships_content_restricted_message_no_products', 'th_custom_restriction_message', 10, 2 );

function th_custom_restriction_message( $message, $args ) {

	$message_logged_in = 'You are logged in but do not have Library Membership on your account.<br>
	If you are an entertainment industry professional, you may <a href="' . th_link_to_memberships_section() . '">apply for Library Membership</a>.';

	$message_logged_out = 'This content is only available to members of the Library Membership plan.<br>
	Please {login} if you are a member.<br><br>
	If you are an entertainment industry professional, and need access to the Library, you may <a href="' . th_link_to_memberships_section() . '">apply for membership</a>.<br>
	Your application will be reviewed by Acme FX.';

	$message = is_user_logged_in() ? $message_logged_in : $message_logged_out;

	return $message;

}


