<?php


/**
* Template Name: Library search - BE test
* Description: Used to test SearchWP integration with Genesis
*/

function th_be_search_form() {

	$args = array(
		'action' => '',
		'engine' => 'library_engine',
	);
	$s_name = !empty( $args['engine'] ) ? 'swpquery' : 's'; /** should be 'swpquery' */
	$value = !empty( $_GET[$s_name] ) ? esc_html( $_GET[$s_name] ) : false;     /** should be the submitted search term */
	?>

<!-- /** th */ --> <article class="entry">
	<form role="search" method="get" class="search-form supplemental-engine" action="<?php echo esc_url( $args['action'] ); ?>">
		<label>
			<span class="screen-reader-text">Search for</span>
			<input type="search" class="search-field" placeholder="Enter search terms&hellip;" value="<?php echo $value; ?>" name="<?php echo $s_name;?>" title="Search for" />
			<input type="hidden" name="swpengine" value="<?php echo $args['engine'];?>" />
		</label>
		<!-- <button type="submit" class="search-submit"><i class="icon-search"></i></button>  -->
<!-- /** th */ --><input type="submit" id="searchsubmit" value="<?php echo( esc_attr( '&#xf179;' ) ); ?>" />

	</form>
<!-- /** th */ --> </article>

<?php
}

add_action( 'genesis_after_loop', 'th_be_search_form', 10 );



/**
 * SearchWP Query
 *
 */
function th_be_searchwp_query() {
    if( empty( $_GET['swpquery'] ) )
        return;
    $args = array(
        's'      => esc_attr( $_GET['swpquery'] ),
        'engine' => esc_attr( $_GET['library_engine'] ),
        'page'   => get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1,
    );
	// $tax_query = ea_get_tax_query();
// /** th */ $tax_query = '';
//     if( !empty( $tax_query ) )
//         $args['tax_query'] = $tax_query;
    $loop = new SWP_Query( $args );
    global $wp_query;
    $wp_query->max_num_pages = $loop->max_num_pages; // for pagination
    $wp_query->posts = $loop->posts; // for loop
}
// add_action( 'tha_content_loop', 'ea_searchwp_query', 9 );
// add_action( 'tha_content_loop', 'wp_reset_query', 11 );

add_action( 'genesis_before', 'th_be_searchwp_query', 9 );
add_action( 'genesis_after_loop', 'wp_reset_query', 11 );

genesis();


