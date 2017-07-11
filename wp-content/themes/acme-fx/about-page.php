<?php

/**
* Template Name: About Page
* Description: Used as a documents listings page
*/


add_action( 'genesis_after_entry_content', 'th_partner_repeater' );

function th_partner_repeater() {

	$section_title = esc_html( get_post_meta( get_the_ID(), 'team_section_title', true ) );

	$team_members = get_post_meta( get_the_ID(), 'about-team', true );
	$all_meta = get_post_meta( get_the_ID() );  // for testing only

	$partners_array = array();

	if( $team_members ) {

		for( $i = 0; $i < $team_members; $i++ ) {

			$partners_array[] = $i;

	}

	shuffle( $partners_array );


		echo '<hr>
				<h2>' . $section_title . '</h2>
				<div class="all-cameos">';

		foreach( $partners_array as $i ) {

	 		$name = esc_html( get_post_meta( get_the_ID(), 'about-team_' . $i . '_name', true ) );

	 			$page_id = (int) get_post_meta( get_the_ID(), 'about-team_' . $i . '_personal-page', true );
			$page_url = esc_url( wp_get_attachment_url( $page_id ) );

				$image = (int) get_post_meta( get_the_ID(), 'about-team_' . $i . '_image', true );
			$image_url = $image ? wp_get_attachment_image( $image, 'thumbnail' ) : '<img src="' . get_stylesheet_directory_uri() . '/images/default-gravatar.png" />';

			echo '<a class="cameo" href="' . $page_url . ' ">' . 
					$image_url . 
					'<p>' . $name . '</p></a>';

		}

		echo '</div>';
		// echo '<br>' . var_dump( $partners_array );

	}

}


genesis();