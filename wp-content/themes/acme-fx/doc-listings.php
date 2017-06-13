<?php

/**
* Template Name: Doc Listings
* Description: Used as a documents listings page
*/

add_action( 'genesis_after_entry_content', 'th_doc_repeater' );

function th_doc_repeater() {

	$docs = get_post_meta( get_the_ID(), 'doc_info', true );
	$all_meta = get_post_meta( get_the_ID() );

	if( $docs ) {

		echo '<ul class="">';

		for( $i = 0; $i < $docs; $i++ ) {

	 		$title = esc_html( get_post_meta( get_the_ID(), 'doc_info_' . $i . '_title', true ) );
			$doc_id = (int) get_post_meta( get_the_ID(), 'doc_info_' . $i . '_url', true );
			$year = (int) get_post_meta( get_the_ID(), 'doc_info_' . $i . '_year', true );
			$pages = (int)  get_post_meta( get_the_ID(), 'doc_info_' . $i . '_pages', true );

			$url = wp_get_attachment_url( $doc_id );
			$url_prepend = 'http://acmefx.dev/wp-content/plugins/pdfjs-viewer-shortcode/pdfjs/web/viewer.php?file=';
			$url_encoded = urlencode( $url );
			$url_append = '&download=true&print=true&openfile=false';
			$pdf_js_url_complete = $url_prepend . $url_encoded . $url_append;

			$page_s = $pages > 1 ? ' pages' : ' page';

			$file_size = filesize( get_attached_file( $doc_id ) );
			$file_size_nice = size_format( $file_size, 2 );

			echo '<li><a href="' . $pdf_js_url_complete . '" target="_blank">' . $title . '</a><p><span class="doc-year">' . $year . '</span>&nbsp; &middot; &nbsp;' . $pages . $page_s . '&nbsp; &middot; &nbsp;<span class="doc-file-size">' . $file_size_nice . '</span></p></li>';

		}

		echo '<ul>';

	}

}

genesis();