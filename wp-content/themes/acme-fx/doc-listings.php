<?php

/**
* Template Name: Doc Listings
* Description: Used as a documents listings page
 *
 * This version of the template checks of migration to Document CPT has been done for this page.  If not, repeater entries' data is used to create new posts.
*/
 

add_action( 'genesis_after_entry_content', 'th_doc_repeater' );

function th_doc_repeater() {

	$docs = get_post_meta( get_the_ID(), 'doc_info', true );

	// for testing only
//	$all_meta = get_post_meta( get_the_ID() );

	if( $docs ) {

		echo '<ul class="doc-listings">';

		for( $i = 0; $i < $docs; $i++ ) {

	 		$title = esc_html( get_post_meta( get_the_ID(), 'doc_info_' . $i . '_title', true ) );
			$doc_id = (int) get_post_meta( get_the_ID(), 'doc_info_' . $i . '_url', true );
			$year = (int) get_post_meta( get_the_ID(), 'doc_info_' . $i . '_year', true );
			$pages = (int)  get_post_meta( get_the_ID(), 'doc_info_' . $i . '_pages', true );

			$maybe_year_with_middot = $year > 0 ? '<span class="doc-year">' . $year . '</span>&nbsp; <span class="pages-and-size">&middot; &nbsp;</span>' : '';

			$url = esc_url( wp_get_attachment_url( $doc_id ) );
			$url_prepend = get_site_url() . '/wp-content/plugins/pdfjs-viewer-shortcode/pdfjs/web/viewer.php?file=';
			$url_encoded = urlencode( $url );
			$url_append = '&download=true&print=true&openfile=false';
			$pdf_js_url_complete = $url_prepend . $url_encoded . $url_append;

			$page_s = $pages > 1 ? ' pages' : ' page';

			$file_size = filesize( get_attached_file( $doc_id ) );
			// Human readable, while showing 2 decimal places for files larger than 1MB
			$file_size_nice = $file_size > 1048576 ? size_format( $file_size, 2 ) : size_format( $file_size, 0 );

			echo '<li><a href="' . $pdf_js_url_complete . '" target="_blank">' . $title . '</a><p>' . $maybe_year_with_middot . '<span class="pages-and-size">' . $pages . $page_s . '&nbsp; &middot; &nbsp;' . $file_size_nice . '</span></p></li>';

			if ( ! th_is_migration_done() ) {

				th_migrate_fields_to_CPT( $title, $doc_id, $year, $pages );

			}

		}

		echo '<ul>';
	}
	
}

add_action( 'genesis_after_content', 'th_mark_migration_done' );
function th_mark_migration_done() {
	update_post_meta( get_the_ID(), 'th-migration_done', true);
}


genesis();



function th_is_migration_done() {

	return get_post_meta( get_the_ID(), 'th-migration_done', true );

}


function th_migrate_fields_to_CPT( $title, $doc_id, $year, $pages ) {

	$parent_page = get_queried_object_id();

	$tax_by_page_id = array(
		541 => 'manual',
		557 => 'sds',
		687 => 'spec-sheet'
	);

	// 'post_name' (slug) is omitted. It will be created from post title.
	$args = array(
		'post_title' => $title,
		'post_type' => 'document',
		'post_status' => 'draft',
		'post_author' => 7,
		'comment_status' => 'closed',
		'ping_status' => 'closed',
		'tax_input' => array(
			'doc_type' => array(
				$tax_by_page_id[ $parent_page ]
			)
		),
		'meta_input' => array(
			'doc_url' => $doc_id,
			'G-doc-info_year' => $year,
			'G-doc-info_pages' => $pages
		)
	);

	wp_insert_post( $args );

}

