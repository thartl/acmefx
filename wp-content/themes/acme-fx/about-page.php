<?php

/**
* Template Name: About Page
* Description: Used as a documents listings page
*/


// Enqueue shuffle script (cache-busting)
add_action( 'wp_enqueue_scripts', 'th_enqueue_shuffle_on_about' );
function th_enqueue_shuffle_on_about() {

	$script_uri      = get_stylesheet_directory_uri() . '/js/th_shuffle_children.js';
	$script_location = get_stylesheet_directory() . '/js/th_shuffle_children.js';
	$last_modified       = date( "Y-m-d_h.i.s", filemtime( $script_location ) );

	// Enqueue the script.
	wp_enqueue_script( 'th_shuffle_children', $script_uri, array( 'jquery' ), $last_modified, true );

}


// todo: really don't need *three* repeaters, should refactor
add_action( 'genesis_after_entry_content', 'th_partner_repeater', 10 );
add_action( 'genesis_after_entry_content', 'th_staff_repeater', 12 );
add_action( 'genesis_after_entry_content', 'th_former_partner_repeater', 14 );
add_action( 'genesis_after_entry_content', 'th_main_credits_loop', 16 );



/**
 * Display the Team section Repeater field entries -- The Partners
 *
 **/
function th_partner_repeater() {

	$team_section_title = esc_html( get_post_meta( get_the_ID(), 'team_section_title', true ) );

	$team_members = get_post_meta( get_the_ID(), 'about-team', true );

	$partners_array = array();

	if( $team_members ) {

		for( $i = 0; $i < $team_members; $i++ ) {

			$partners_array[] = $i;

		}

		/** Using th_shuffle_children.js instead */
//		shuffle( $partners_array );

		echo '<hr>
				<b id="partners" class="raise-anchor">&nbsp</b>
				<h2>' . $team_section_title . '</h2>
				<div class="all-cameos shuffle-children">';

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
//	$all_meta = get_post_meta( get_the_ID() );  // for testing only

	$staff_array = array();

	if( $staff_members ) {

		for( $i = 0; $i < $staff_members; $i++ ) {

			$staff_array[] = $i;

		}

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

	}
}


/**
 * Display the Former Team section Repeater field entries -- Former Partners
 *
 **/
function th_former_partner_repeater() {

	$team_section_title = esc_html( get_post_meta( get_the_ID(), 'former_team_section_title', true ) );

	$team_members = get_post_meta( get_the_ID(), 'about-former_team', true );

	$partners_array = array();

	if( $team_members ) {

		for( $i = 0; $i < $team_members; $i++ ) {

			$partners_array[] = $i;

		}

		echo '<hr>
				<b id="former-partners" class="raise-anchor">&nbsp</b>
				<h2>' . $team_section_title . '</h2>
				<div class="all-cameos">';

		foreach( $partners_array as $i ) {

			$name = esc_html( get_post_meta( get_the_ID(), 'about-former_team_' . $i . '_name', true ) );

			$page_id = (int) get_post_meta( get_the_ID(), 'about-former_team_' . $i . '_personal-page', true );
			$page_url = esc_url( get_page_link( $page_id ) );

			$image = (int) get_post_meta( get_the_ID(), 'about-former_team_' . $i . '_image', true );
			$image_url = $image ? wp_get_attachment_image( $image, 'thumbnail' ) : '<img src="' . get_stylesheet_directory_uri() . '/images/default-gravatar.png" />';

			echo '<a class="cameo" href="' . $page_url . ' ">' .
			     $image_url .
			     '<p>' . $name . '</p></a>';

		}

		echo '</div>';

	}

}


/************  Display the Credits  ***********************/
/**
 *
 **/

function th_main_credits_loop() {

	$credits_section_title = esc_html( get_post_meta( get_the_ID(), 'credits_section_title', true ) );

	if( $credits_section_title ) {

		echo '<hr>
			<b id="past-productions" class="raise-anchor">&nbsp</b>
			<h2>' . $credits_section_title . '</h2>';

	}


	$front_end_priority = 0;
	if ( current_user_can( 'administrator' ) && get_user_meta( get_current_user_id(),'show_credit_priority_on_front_end' , true ) ) {
		$front_end_priority = 1;
	}

 	$priority = 'acme_credit_priority';

	$args = array(
		'post_type'	=> 'credits',
		'post_status' => 'publish',
		'tax_query' => array(
				array(
					'taxonomy' => 'credit_share',
					'field' => 'name',
					'terms' => 'Acme',
				)
			),
		'meta_query' => array(
			'relation' => 'AND',  // "AND" forces query to read data in 'date_clause', so it can be used for 'orderby'
        	array(
        		'relation' => 'OR',
        		'priority_clause' => array(
        			'key' => $priority,
        			'type' => 'NUMERIC',
        			'compare' => 'EXISTS',
        			),
        		'unused_clause' => array(  // In case priority key is missing, credit will still publish
        			'key' => $priority,
        			'compare' => 'NOT EXISTS',
        			),
        		),	
			'date_clause' => array(
				'key' => 'release_date',
				'type' => 'DATE',
				),
        	),
        'orderby' => array(
        	'priority_clause' => 'DESC',
        	'date_clause' => 'DESC',
        	),
		'posts_per_page' => -1,
	);


	$loop = new WP_Query( $args );

	if ( $loop->have_posts() ) :

		echo '<article class="page entry">';
		echo '<ul class="credits-list" >';


		while ( $loop->have_posts() ) : $loop->the_post(); 

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

			$print_priority = get_post_meta( $credit_id, 'acme_credit_priority', true );

			$show_priority = $front_end_priority ? '<p class="front-end-priority">' . $print_priority . '</p>' : '';


			echo '<li><a href="' . $url . '" target="_blank" ><div class="match-height-item" >' . $image_url . '</div><p>' . 
			$title . '</p><p>' . $show_date . '</p><p>' . $project_type . '</p>' .
			$show_priority .
			'</a></li>';

		endwhile;


		echo '</ul>';
		echo '</article>';

	endif;

	wp_reset_postdata();}


genesis();
