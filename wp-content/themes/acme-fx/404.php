<?php
/**
* This is the 404 template
* 
*/


// Remove default loop.
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_loop', 'genesis_404' );
/**
 * This function outputs a 404 "Not Found" error message.
 *
 * @since 1.6
 */
function genesis_404() {

	genesis_markup( array(
		'open' => '<article class="entry">',
		'context' => 'entry-404',
	) );

		printf( '<h1 class="entry-title">%s</h1>', apply_filters( 'genesis_404_entry_title', __( 'Not found, error 404', 'genesis' ) ) );
		echo '<div class="entry-content">';

			if ( genesis_html5() ) :

				echo apply_filters( 'genesis_404_entry_content', '<p>' . sprintf( __( 'The page you are looking for no longer exists. Perhaps you can return back to the site\'s <a href="%s">homepage</a> and see if you can find what you are looking for. Or, you can try finding it by using the search form below.', 'genesis' ), trailingslashit( home_url() ) ) . '</p>' );

				get_search_form();

			else :
	?>

			<p><?php printf( __( 'The page you are looking for no longer exists. Perhaps you can return back to the site\'s <a href="%s">homepage</a> and see if you can find what you are looking for. Or, you can try finding it with the information below.', 'genesis' ), trailingslashit( home_url() ) ); ?></p>



	<?php
			endif;

			// *************************** Sitemap is deactivated ****
			// if ( genesis_a11y( '404-page' ) ) {
			// 	echo '<h2>' . __( 'Sitemap', 'genesis' ) . '</h2>';
			// 	genesis_sitemap( 'h3' );
			// } else {
			// 	genesis_sitemap( 'h4' );
			// }

		echo '</div>';

	genesis_markup( array(
		'close' => '</article>',
		'context' => 'entry-404',
	) );

}

genesis();
