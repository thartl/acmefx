<?php

/**
* Template Name: Admin docs
* Description: Admin manuals etc.
*
* This page template lists its childern pages and includes a publish or modified date, whichever is later
*/
 

function th_admin_docs_loop() {
	global $post;

	// 
	$args = array(
		'post_type'	=> 'page',
		'post_parent' => $post->ID,
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'orderby' => 'date',
		'order' => 'ASC',
	);

	$loop = new WP_Query( $args );


	if ( $loop->have_posts() ) : 

		echo '<ul class="doc-list" >';


		while ( $loop->have_posts() ) : $loop->the_post(); 

			$title = get_the_title();
			$title = sprintf( '<a href="%s" rel="bookmark" >%s</a>', get_permalink(), $title );


			if ( get_the_date( 'Y-m-d-H' ) !== get_the_modified_date( 'Y-m-d-H' ) ) { # Modified date
				$post_info = sprintf( '<i class="fa fa-calendar"></i>&nbsp; <em>Updated &nbsp;</em> <time class="entry-time" itemprop="dateModified" datetime="%s">%s</time>', get_the_modified_date( 'Y-m-d' ), get_the_modified_date() );
			} else { # Published date
				$post_info = sprintf( '<i class="fa fa-calendar"></i> &nbsp;&nbsp;<time class="entry-time" itemprop="datePublished" datetime="%s">%s</time>', get_the_date( 'Y-m-d' ), get_the_date() );
			}


			echo '<li><p>' . $title . '&nbsp; &middot; &nbsp; <span class="pub-mod-date" >' . $post_info . '</span></p></li>';

		endwhile;


		echo '</ul>';

		do_action( 'genesis_after_endwhile' );

	endif;


	wp_reset_postdata();}


add_action( 'genesis_after_entry_content', 'th_admin_docs_loop' );
//remove_action( 'genesis_loop', 'genesis_do_loop' );  // commented out to include regular page content



genesis();