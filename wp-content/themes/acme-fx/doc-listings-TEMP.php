<?php

/**
 * Template Name: Doc Listings - TRANSITIONAL
 * Description: Used as a documents listings page
 */


add_action( 'genesis_after_loop', 'th_render_transitional_listings' );
function th_render_transitional_listings() {

	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

	$args = array(
		'post_type'      => 'document',
		'post_status'    => 'publish',
		'posts_per_page' => - 1,
		'paged'          => $paged,
		'orderby'        => 'title',
		'order' => 'ASC'
	);

	$select_library_page = esc_html( get_post_meta( get_the_ID(), 'select_library_page', true ) );

	if ( $select_library_page ) {

		$tax_query_args = array(
			'tax_query' => array(
				array(
					'taxonomy' => 'doc_type',
					'field' => 'slug',
					'terms' => $select_library_page,
				)
			),
		);

		$args = array_merge( $args, $tax_query_args );

	}


	$loop = new WP_Query( $args );

	echo '<article class="page entry library-block">';

	if ( $loop->have_posts() ) :

		echo '<ul class="doc-listings">';

		while ( $loop->have_posts() ) : $loop->the_post();

			$title          = get_the_title();
			$doc_id         = (int) get_post_meta( get_the_ID(), 'doc_url', true );
			$year_published = (int) get_post_meta( get_the_ID(), 'G-doc-info_year', true );
			$doc_page_count = (int) get_post_meta( get_the_ID(), 'G-doc-info_pages', true );

			$maybe_year_with_middot = $year_published > 0 ?
				'<span class="doc-year">' . $year_published . '</span>&nbsp; <span class="pages-and-size">&middot; &nbsp;</span>' :
				'';

			$url                 = esc_url( wp_get_attachment_url( $doc_id ) );
			// no longer using PDFjs
//			$url_prepend         = get_site_url() . '/wp-content/plugins/pdfjs-viewer-shortcode/pdfjs/web/viewer.php?file=';
//			$url_encoded         = urlencode( $url );
//			$url_append          = '&download=true&print=true&openfile=false';
//			$pdf_js_url_complete = $url_prepend . $url_encoded . $url_append;

			$page_s = $doc_page_count > 1 ? ' pages' : ' page';

			$file_size = filesize( get_attached_file( $doc_id ) );
			// Human readable, while showing 2 decimal places for files larger than 1MB
			$file_size_nice = $file_size > 1048576 ? size_format( $file_size, 2 ) : size_format( $file_size, 0 );

			echo '<li><a href="' . $url . '" target="_blank">' . $title . '</a><p>' . $maybe_year_with_middot . '<span class="pages-and-size">' . $doc_page_count . $page_s . '&nbsp; &middot; &nbsp;' . $file_size_nice . '</span></p></li>';

		endwhile;

		echo '<ul>';

	endif;

	echo '</article>';


	wp_reset_postdata();


}

genesis();