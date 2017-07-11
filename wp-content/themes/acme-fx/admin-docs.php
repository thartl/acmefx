<?php

/**
* Template Name: Admin docs
* Description: Admin manuals etc.
*/


/* Template Name: Test */
/**
 * Genesis custom loop
 */


function th_custom_loop() {
	global $post;

	// 
	$args = array(
		'post_type'	=> 'page',
		'post_parent' => $post->ID,
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'orderby' => 'modified',
		'order' => 'DESC'
	);


	// Use $loop, a custom variable we made up, so it doesn't overwrite anything
	$loop = new WP_Query( $args );


	// have_posts() is a wrapper function for $wp_query->have_posts(). Since we
	// don't want to use $wp_query, use our custom variable instead.

	if ( $loop->have_posts() ) : 

		echo '<ul class="doc-list" >';


		while ( $loop->have_posts() ) : $loop->the_post(); 

			$title = get_the_title();
			$title = sprintf( '<a href="%s" rel="bookmark" >%s</a>', get_permalink(), $title );


			if ( get_the_date( 'Y-m' ) !== get_the_modified_date( 'Y-m' ) ) { # Modified date
				$post_info = sprintf( '<i class="fa fa-calendar"></i>&nbsp; <em>Updated:</em> <time class="entry-time" itemprop="dateModified" datetime="%s">%s</time>', get_the_modified_date( 'Y-m-d' ), get_the_modified_date() );
			} else { # Published date
				$post_info = sprintf( '<i class="fa fa-calendar"></i> &nbsp;&nbsp;<time class="entry-time" itemprop="datePublished" datetime="%s">%s</time>', get_the_date( 'Y-m-d' ), get_the_date() );
			}





			echo '<li><p>' . $title . '&nbsp; &middot; &nbsp; <span class="pub-mod-date" >' . $post_info . '</span></p></li>';

		endwhile;


		echo '</ul>';

		do_action( 'genesis_after_endwhile' );
	endif;


	// We only need to reset the $post variable. If we overwrote $wp_query,
	// we'd need to use wp_reset_query() which does both.
	wp_reset_postdata();}


add_action( 'genesis_after_entry_content', 'th_custom_loop' );
//remove_action( 'genesis_loop', 'genesis_do_loop' );



genesis();