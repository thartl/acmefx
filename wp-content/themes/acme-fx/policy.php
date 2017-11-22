<?php

/**
* Template Name: Policy Page
* Description: Used for Privacy Policy / Terms and Conditions
*/
 

add_action( 'genesis_before_entry_content', 'ab_policy_gist_section', 15 );



/** Display Gist Section before Entry Content
 *
 **/

function ab_policy_gist_section() {
	
	
	$gist_title = esc_html( get_post_meta( get_the_ID(), 'gist_title', true ) );
	$gist_content = esc_html( get_post_meta( get_the_ID(), 'gist_content', true ) );

	if ( !empty ( $gist_content ) ) {
		if  ( !empty ( $gist_title ) ) {

		echo '<div class="gist"><h2>' . $gist_title . '</h2><br><div class="gist-content">' . $gist_content . '</div></div>';
		} else {
			echo '<div class="gist"><div class="gist-content">' . $gist_content . '</div></div>';
		}
	}


}

function ab_policy_body_classes( $classes ) {

	$gist_title = esc_html( get_post_meta( get_the_ID(), 'gist_title', true ) );
	$gist_content = esc_html( get_post_meta( get_the_ID(), 'gist_content', true ) );

	if ( !empty ( $gist_content ) ) {
		if  ( !empty ( $gist_title ) ) {
			$gist_on = 'policy-gist';
		
		} else {
			$gist_on = 'policy-gist-no-title';
		}
	} else {
		$gist_on = 'policy-no-gist';
	}



	$classes[] = $gist_on; 
	return $classes;

}


add_filter ( 'body_class', 'ab_policy_body_classes' );


genesis();