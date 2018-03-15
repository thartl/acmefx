<?php

/**
* Template Name: Bio Page
* Description: Used as a documents listings page
*/


/** Display the Credits
 *
 **/

function th_individual_credits_loop() {
	global $post;

	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

	$front_end_priority = 0;
	if ( current_user_can( 'administrator' ) && get_user_meta( get_current_user_id(),'show_credit_priority_on_front_end' , true ) ) {
		$front_end_priority = 1;
	}

 	$sync_name = esc_html( get_post_meta( get_the_ID(), 'credits_sync_name', true ) );

 	$priority = strtolower( $sync_name ) . '_credit_priority';

	$args = array(
		'post_type'	=> 'credits',
		'post_status' => 'publish',
		'tax_query' => array(
				array(
					'taxonomy' => 'credit_share',
					'field' => 'name',
					'terms' => $sync_name,
				)
			),
		'meta_query' => array(
			'relation' => 'AND',  // "AND" forces query to read data in 'date_clause', so it can be used for 'orderby' !!!!
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
		'paged' => $paged,
	);


	$loop = new WP_Query( $args );

	$personal_imdb = esc_url( get_post_meta( get_the_ID(), 'personal_imdb_link', true ) );


		echo '<article class="page entry">';

		echo '<hr><p>Check out ' . $sync_name . '\'s <a href="' . $personal_imdb . '" target="_blank" >IMDb resume</a>';


	if ( $loop->have_posts() ) :

		echo ' or have a look at some productions ' . $sync_name . ' has worked on:</p>';

		echo '<div class="credits-list" >';



		while ( $loop->have_posts() ) : $loop->the_post();

					$credit_id = get_the_ID();

					$title = get_the_title();

						$image = (int) get_post_meta( $credit_id, 'poster_image', true );
					$image_url = $image ? wp_get_attachment_image( $image, 'full' ) : '';

						$release_date = (int) get_post_meta( $credit_id, 'release_date', true );
						$year = substr( $release_date , 0, 4 );
						$front_end_date = esc_html( get_post_meta( $credit_id, 'front_end_date', true ) );
					$show_date = $front_end_date ? $front_end_date : $year;

					$url = esc_url( get_post_meta( $credit_id, 'imdb_link', true ) );

					$project_type = esc_html( get_post_meta( $credit_id, 'project_type', true ) );

					$print_priority = get_post_meta( $credit_id, $priority, true );

					$show_priority = $front_end_priority ? '<p class="front-end-priority">' . $print_priority . '</p>' : '';


					echo '<li><a href="' . $url . '" target="_blank" ><div class="match-height-item" >' . $image_url . '</div><p>' . 
					$title . '</p><p>' . $show_date . '</p><p>' . $project_type . '</p>' .
					$show_priority .
					'</a></li>';

		endwhile;

		echo '</div>';

		do_action( 'genesis_after_endwhile' );

	endif;

		echo '</article>';



	// We only need to reset the $post variable. If we overwrote $wp_query,
	// we'd need to use wp_reset_query() which does both.
	// In other words, wp_rest_query is only necessary if query_posts() was used.

	wp_reset_postdata();

}


add_action( 'genesis_loop', 'th_individual_credits_loop', 15 );

genesis();