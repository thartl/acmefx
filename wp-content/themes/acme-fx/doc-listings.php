<?php

/**
* Template Name: Doc Listings
* Description: Used as a documents listings page
*/
 

add_action( 'genesis_after_entry_content', 'th_doc_repeater' );

function th_doc_repeater() {

	$docs = get_post_meta( get_the_ID(), 'doc_info', true );
	$all_meta = get_post_meta( get_the_ID() );  // for testing only

	if( $docs ) {

<<<<<<< HEAD
		echo '<ul class="">';
=======
		echo '<ul class="doc-listings">';
>>>>>>> 2e87e513cbc1cfae91d0ba58819db76ef4157f15

		for( $i = 0; $i < $docs; $i++ ) {

	 		$title = esc_html( get_post_meta( get_the_ID(), 'doc_info_' . $i . '_title', true ) );
			$doc_id = (int) get_post_meta( get_the_ID(), 'doc_info_' . $i . '_url', true );
			$year = (int) get_post_meta( get_the_ID(), 'doc_info_' . $i . '_year', true );
			$pages = (int)  get_post_meta( get_the_ID(), 'doc_info_' . $i . '_pages', true );

<<<<<<< HEAD
=======
			$maybe_year_with_middot = $year > 0 ? '<span class="doc-year">' . $year . '</span>&nbsp; <span class="pages-and-size">&middot; &nbsp;</span>' : '';

>>>>>>> 2e87e513cbc1cfae91d0ba58819db76ef4157f15
			$url = esc_url( wp_get_attachment_url( $doc_id ) );
			$url_prepend = get_site_url() . '/wp-content/plugins/pdfjs-viewer-shortcode/pdfjs/web/viewer.php?file=';
			$url_encoded = urlencode( $url );
			$url_append = '&download=true&print=true&openfile=false';
			$pdf_js_url_complete = $url_prepend . $url_encoded . $url_append;

			$page_s = $pages > 1 ? ' pages' : ' page';

			$file_size = filesize( get_attached_file( $doc_id ) );
<<<<<<< HEAD
			$file_size_nice = size_format( $file_size, 2 );

			echo '<li><a href="' . $pdf_js_url_complete . '" target="_blank">' . $title . '</a><p><span class="doc-year">' . $year . '</span>&nbsp; &middot; &nbsp;' . $pages . $page_s . '&nbsp; &middot; &nbsp;<span class="doc-file-size">' . $file_size_nice . '</span></p></li>';
=======
			// Human readable, while showing 2 decimal places for files larger than 1MB
			$file_size_nice = $file_size > 1048576 ? size_format( $file_size, 2 ) : size_format( $file_size, 0 );

			echo '<li><a href="' . $pdf_js_url_complete . '" target="_blank">' . $title . '</a><p>' . $maybe_year_with_middot . '<span class="pages-and-size">' . $pages . $page_s . '&nbsp; &middot; &nbsp;' . $file_size_nice . '</span></p></li>';
>>>>>>> 2e87e513cbc1cfae91d0ba58819db76ef4157f15

		}

		echo '<ul>';
	}
}

genesis();