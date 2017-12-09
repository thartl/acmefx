<?php

/**
* Template Name: Library search - JW test
* Description: Used to test SearchWP integration with Genesis
*/
 

function th_searchwp_form( $query ) {
	/*th*/ echo '<article class="page entry">';
	echo '<form class="searchwp-form" action="" method="get">';
	echo '<input type="text" id="searchwpquery" name="searchwpquery" value="' . esc_attr( $query ) . '" />';
	echo '<button type="submit">' . __( 'Search', 'text-domain' ) . '</button>';
	echo '</form>';
}
function mySearchEnginePostsPerPage() {
	return 6; // 10 posts per page
}
add_filter( 'searchwp_posts_per_page', 'mySearchEnginePostsPerPage' );
function th_do_search_loop() {
	global $wp_query, $post;
	// Return early if SearchWP is disabled.
	if ( ! class_exists( 'SearchWP' ) )
		return;
	$searchwpquery = isset( $_GET['searchwpquery'] ) ? sanitize_text_field( $_GET['searchwpquery'] ) : '';
	$searchwppage = get_query_var( 'paged' );
	if( empty( $searchwppage ) ) $searchwppage = 1;
	// Load the custom SearchWP form.
	th_searchwp_form( $searchwpquery );
	// Do nothing if no search has been performed.
	if ( empty( $searchwpquery ) )
		return;
	// Instantiate SearchWP.
	$engine = SearchWP::instance();
	// Set the supplemental search engine name.
	$supplementalSearchEngineName = 'default';
	// perform the search
	$posts = $engine->search( $supplementalSearchEngineName, $searchwpquery, $searchwppage );
	// prep pagiation
	$original = array( 'max_num_pages' => $wp_query->max_num_pages, 'found_posts' => $wp_query->found_posts );
	$wp_query->max_num_pages = $engine->maxNumPages;
	$wp_query->found_posts = $engine->foundPosts;
	
	// trick subsequent function calls into thinking we're not on a page but in a loop
	$wp_query-> is_singular = $wp_query->is_single = $wp_query->is_page = false;
	// output our loop
	if( !empty( $posts ) ) {
		foreach ( $posts as $post )
		{
			setup_postdata( $post );
			?>
				<p><?php the_title(); ?></p>
			<?php
		}
		/*th*/ echo '</article>';
		// this action already fires automatically if we're NOT on page 1, but only for prev/next
		if( 'numeric' === genesis_get_option( 'posts_nav' ) || ( 'numeric' !== genesis_get_option( 'posts_nav' ) && $searchwppage < 2 ) )
			do_action( 'genesis_after_endwhile' );
	}
	$wp_query->max_num_pages = $original['max_num_pages'];
	$wp_query->found_posts = $original['found_posts'];
}
add_action( 'genesis_after_entry', 'th_do_search_loop' );

genesis();