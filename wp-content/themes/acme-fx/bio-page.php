<?php

/**
* Template Name: Bio Page
* Description: Used as a documents listings page
*/


add_action( 'genesis_after_entry_content', 'th_individual_credits_loop', 15 );



/** Display the Credits
 *
 **/

function th_individual_credits_loop() {
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


	$loop = new WP_Query( $args );


 	$sync_name = esc_html( get_post_meta( get_the_ID(), 'credits_sync_name', true ) );

	$personal_imdb = esc_url( get_post_meta( get_the_ID(), 'personal_imdb_link', true ) );


	if ( $loop->have_posts() ) :


		echo '<hr><h5>Check out ' . $sync_name . '\'s <a href="' . $personal_imdb . '" target="_blank" >IMDb resume</a> or have a look at some productions ' . $sync_name . ' has worked on:</h5>';

		echo '<div class="credits-list" >';



		while ( $loop->have_posts() ) : $loop->the_post(); 

			$credit_partner_array = get_post_meta( get_the_ID(), 'partner_credits', true );

			if( in_array( $sync_name, $credit_partner_array ) ) {

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


				echo '<li><a href="' . $url . '" target="_blank" >' . $image_url . '<p>' . $title . '</p><p>' . $year . '</p><p>' . $project_type . '</p></a></li>';

			}

	// $all_meta = get_post_meta( get_the_ID() );  // for testing only
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
	wp_reset_postdata();

}



genesis();