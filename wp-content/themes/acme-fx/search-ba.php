<?php

/**
* Template Name: Library search - BE test
* Description: Used to test SearchWP integration with Genesis
*/
 
/**  The Form  */
function th_supplemental_engine_form () {

	$th_custom_args = array();
	$args = wp_parse_args( $th_custom_args, array(
		// 'action' => home_url( '/' ),
		'action' => '', /** try: send result back to this template **/
		'engine' => 'library-engine',
		// 'engine' => false,
	));
	$s_name = !empty( $args['engine'] ) ? 'swpquery' : 's';
	$value = !empty( $_GET[$s_name] ) ? esc_html( $_GET[$s_name] ) : false;
	?>

	<form role="search" method="get" class="search-form" action="<?php echo esc_url( $args['action'] ); ?>">
		<label>
			<span class="screen-reader-text">Search for</span>
			<input type="search" class="search-field" placeholder="Enter search terms&hellip;" value="<?php echo $value; ?>" name="<?php echo $s_name;?>" title="Search for" />
			<input type="hidden" name="swpengine" value="<?php echo $args['engine'];?>" />
		</label>
		<button type="submit" class="search-submit"><i class="icon-search"></i></button>
	</form>

<?php
}


/**  The Results  */
function ea_searchwp_query() {
    if( empty( $_GET['swpquery'] ) )
        return;
    $args = array(
        's'      => esc_attr( $_GET['swpquery'] ),
        'engine' => esc_attr( $_GET['swpengine'] ),
        'page'   => get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1,
    );
	// $tax_query = ea_get_tax_query();
    if( !empty( $tax_query ) )
        $args['tax_query'] = $tax_query;
    $loop = new SWP_Query( $args );
    global $wp_query;
    $wp_query->max_num_pages = $loop->max_num_pages; // for pagination
    $wp_query->posts = $loop->posts; // for loop
}

add_action( 'genesis_after_entry_content', 'th_supplemental_engine_form', 8 );

// add_action( 'genesis_after_entry_content', 'ea_searchwp_query', 9 );
// add_action( 'genesis_after_entry_content', 'wp_reset_query', 11 );

genesis();