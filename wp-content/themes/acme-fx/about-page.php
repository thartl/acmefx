<?php

/**
* Template Name: About Page
* Description: Used as a documents listings page
*/


add_action( 'genesis_after_entry_content', 'th_partner_repeater', 10 );

add_action( 'genesis_after_entry_content', 'th_staff_repeater', 12 );

add_action( 'genesis_after_entry_content', 'th_main_credits_loop', 15 );



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


/** Display the Team section Repeater field entries -- The Partners
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

		echo '<ul class="credits-list" >';



		while ( $loop->have_posts() ) : $loop->the_post(); 

			$title = get_the_title();

				$image = (int) get_post_meta( get_the_ID(), 'poster_image', true );
			$image_url = $image ? wp_get_attachment_image( $image, 'full' ) : '';

				$release_date = (int) get_post_meta( get_the_ID(), 'release_date', true );
				$year = substr( $release_date , 0, 4 );
				$front_end_date = esc_html( get_post_meta( get_the_ID(), 'front_end_date', true ) );
				$show_date = $front_end_date ? $front_end_date : $year;

			$url = esc_url( get_post_meta( get_the_ID(), 'imdb_link', true ) );

			$project_type = esc_html( get_post_meta( get_the_ID(), 'project_type', true ) );



			echo '<li><a href="' . $url . '" target="_blank" ><div class="match-height-item" >' . $image_url . '</div><p>' . $title . '</p><p>' . $show_date . '</p><p>' . $project_type . '</p></a></li>';


	// $all_meta = get_post_meta( get_the_ID() );  // for testing only
	// var_dump( $all_meta );
	// var_dump( $post );
	// var_dump( $credit_partner_array );
	// var_dump( wp_get_additional_image_sizes() );

		endwhile;

		echo '</ul>';

// $current_user_object = wp_get_current_user();
// $current_username = $current_user_object->user_login;

// echo 'Username: ' . $current_username . '<br>';
// var_dump( $current_user_object );


		do_action( 'genesis_after_endwhile' );

	endif;

	wp_reset_postdata();}


genesis();