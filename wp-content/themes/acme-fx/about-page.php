<?php

/**
* Template Name: About Page
* Description: Used as a documents listings page
*/



add_action( 'genesis_after_entry_content', 'th_partner_repeater', 10 );

add_action( 'genesis_after_entry_content', 'th_staff_repeater', 12 );


/** Display the Team section Repeater field entries -- The Partners
 *
 *
 *
 **/

function th_partner_repeater() {

	$team_section_title = esc_html( get_post_meta( get_the_ID(), 'team_section_title', true ) );

	$team_members = get_post_meta( get_the_ID(), 'about-team', true );
	$all_meta = get_post_meta( get_the_ID() );  // for testing only

	$partners_array = array();

	if( $team_members ) {

		for( $i = 0; $i < $team_members; $i++ ) {

			$partners_array[] = $i;

		}

		shuffle( $partners_array );


		echo '<hr>
				<b id="partners" class="raise-anchor">&nbsp</b>
				<h2>' . $team_section_title . '</h2>
				<div class="all-cameos">';

		foreach( $partners_array as $i ) {

	 		$name = esc_html( get_post_meta( get_the_ID(), 'about-team_' . $i . '_name', true ) );

	 			$page_id = (int) get_post_meta( get_the_ID(), 'about-team_' . $i . '_personal-page', true );
	 		$page_url = esc_url( get_page_link( $page_id ) );

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


/** Display the Team section Repeater field entries -- The Staff
 *
 *
 *
 **/

function th_staff_repeater() {

	$staff_section_title = esc_html( get_post_meta( get_the_ID(), 'staff_section_title', true ) );

	$staff_members = get_post_meta( get_the_ID(), 'about-staff', true );
	$all_meta = get_post_meta( get_the_ID() );  // for testing only

	$staff_array = array();

	if( $staff_members ) {

		for( $i = 0; $i < $staff_members; $i++ ) {

			$staff_array[] = $i;

		}

		shuffle( $staff_array );


		echo '<hr>
				<b id="staff" class="raise-anchor">&nbsp</b>
				<h2>' . $staff_section_title . '</h2>
				<div class="all-cameos">';

		foreach( $staff_array as $i ) {

	 		$name = esc_html( get_post_meta( get_the_ID(), 'about-staff_' . $i . '_name', true ) );

	 			$page_id = (int) get_post_meta( get_the_ID(), 'about-staff_' . $i . '_personal-page', true );
	 		$page_url = esc_url( get_page_link( $page_id ) );

				$image = (int) get_post_meta( get_the_ID(), 'about-staff_' . $i . '_image', true );
			$image_url = $image ? wp_get_attachment_image( $image, 'thumbnail' ) : '<img src="' . get_stylesheet_directory_uri() . '/images/default-gravatar.png" />';

			echo '<a class="cameo" href="' . $page_url . ' ">' . 
					$image_url . 
					'<p>' . $name . '</p></a>';

		}

		echo '</div>';
		// echo '<br>' . var_dump( $partners_array );

	}


	$credits_section_title = esc_html( get_post_meta( get_the_ID(), 'credits_section_title', true ) );

	if( $credits_section_title ) {

		echo '<hr>
			<b id="past-productions" class="raise-anchor">&nbsp</b>
			<h2>' . $credits_section_title . '</h2>';

	}

}



/************  Display the Credits  ***********************/
/**
 *
 **/

function th_main_credits_loop() {
	global $post;

	// 
	$args = array(
		'post_type'	=> 'credits',
		'tax_query' => array(
				array(
					'taxonomy' => 'credit_share',
					'field' => 'name',
					'terms' => 'Acme',
				)
			),
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'meta_key' => 'release_date',
		'meta_type' => 'NUMERIC',
		'orderby' => 'meta_value',
		'order' => 'DESC'
	);


	// Use $loop, a custom variable we made up, so it doesn't overwrite anything
	$loop = new WP_Query( $args );


	// have_posts() is a wrapper function for $wp_query->have_posts(). Since we
	// don't want to use $wp_query, use our custom variable instead.

	if ( $loop->have_posts() ) : 

		echo '<article class="page entry">';
		echo '<ul class="credits-list" >';


		while ( $loop->have_posts() ) : $loop->the_post(); 


// Used to transfer / convert custom field values to taxonomy terms
// $credit_partner_array = get_post_meta( get_the_ID(), 'partner_credits', true );
// wp_set_post_terms( get_the_ID(), $credit_partner_array, 'credit_share' );

// Add credit_share term "Acme" to all Credits -- last param of true = append, as opposed to overwrite
// **** Comment out tax_query above to run through all Credits ****
// wp_set_post_terms( get_the_ID(), 'Acme', 'credit_share', true );

// Zero out all existing credit priorities
$credit_array = array(
	'acme_credit_priority',
	'danny_credit_priority',
	'john_credit_priority',
	'rob_credit_priority',
	'tim_credit_priority',
	'terry_credit_priority',
	'warren_credit_priority',
	);
foreach ($credit_array as $key => $value) {
	update_post_meta( get_the_ID(), $value, 10 );
}

			$credit_id = get_the_ID();

			$title = get_the_title();

				$image = (int) get_post_meta( $credit_id, 'poster_image', true );
			$image_url = $image ? wp_get_attachment_image( $image, 'credit-poster' ) : '';

				$release_date = (int) get_post_meta( $credit_id, 'release_date', true );
				$year = substr( $release_date , 0, 4 );
				$front_end_date = esc_html( get_post_meta( $credit_id, 'front_end_date', true ) );
				$show_date = $front_end_date ? $front_end_date : $year;

			$url = esc_url( get_post_meta( $credit_id, 'imdb_link', true ) );

			$project_type = esc_html( get_post_meta( $credit_id, 'project_type', true ) );


			echo '<li><a href="' . $url . '" target="_blank" ><div class="match-height-item" >' . $image_url . '</div><p>' . $title . '</p><p>' . $show_date . '</p><p>' . $project_type . '</p></a></li>';


		endwhile;

		echo '</ul>';
		echo '</article>';

		do_action( 'genesis_after_endwhile' );

	endif;

	wp_reset_postdata();}


add_action( 'genesis_loop', 'th_main_credits_loop', 15 );

genesis();