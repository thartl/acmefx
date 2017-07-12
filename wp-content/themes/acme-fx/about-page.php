<?php

/**
* Template Name: About Page
* Description: Used as a documents listings page
*/


add_action( 'genesis_after_entry_content', 'th_partner_repeater', 10 );

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


	$credits_section_title = esc_html( get_post_meta( get_the_ID(), 'credits_section_title', true ) );

	if( $credits_section_title ) {

		echo '<hr>
			<h2>' . $credits_section_title . '</h2>';

	}

}



/** Display the Credits
 *
 *			SO FAR UNEDITED, AFTER COPY FROM ADMIN-DOCS.PHP
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

		echo '<div class="credits-grid" >';




		while ( $loop->have_posts() ) : $loop->the_post(); 

			$title = get_the_title();

				$image = (int) get_post_meta( get_the_ID(), 'poster_image', true );
			$image_url = $image ? wp_get_attachment_image( $image, 'full' ) : '';

			$release_date = (int) get_post_meta( get_the_ID(), 'release_date', true );
			$year = substr( $release_date , 0, 4 );
			$front_end_date = esc_html( get_post_meta( get_the_ID(), 'front_end_date', true ) );

			if( $front_end_date ) :
				$year = $front_end_date ? $front_end_date : $year;
			endif;

			$url = esc_url( get_post_meta( get_the_ID(), 'imdb_link', true ) );

			$project_type = esc_html( get_post_meta( get_the_ID(), 'project_type', true ) );


//			$credit_partner_array = get_post_meta( get_the_ID(), 'partner_credits', true );

//			$docs = get_post_meta( get_the_ID(), 'doc_info', true );



			echo '<a href="' . $url . '" target="_blank" style="display:block" >' . $image_url . $title . $year . $project_type . '</a>';


	$all_meta = get_post_meta( get_the_ID() );  // for testing only
	// var_dump( $all_meta );
	// var_dump( $post );
	// var_dump( $credit_partner_array );
	// var_dump( wp_get_additional_image_sizes() );

		endwhile;





		echo '<div>';

		do_action( 'genesis_after_endwhile' );

	endif;


	// We only need to reset the $post variable. If we overwrote $wp_query,
	// we'd need to use wp_reset_query() which does both.
	wp_reset_postdata();}



genesis();