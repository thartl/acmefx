<?php
/**
 *
 * This file adds functions to the Acme FX Theme.
 *
 * @package Acme FX
 * @author  Parkdale Wire
 * @license GPL-2.0+
 * @link    http://www.studiopress.com/
 * @link    http://www.parkdalewire.com/
 */


// Start the engine.
include_once( get_template_directory() . '/lib/init.php' );

// Setup Theme.
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

// Set Localization (do not remove).
add_action( 'after_setup_theme', 'genesis_sample_localization_setup' );
function genesis_sample_localization_setup(){
	load_child_theme_textdomain( 'genesis-sample', get_stylesheet_directory() . '/languages' );
}

// Add the helper functions.
include_once( get_stylesheet_directory() . '/lib/helper-functions.php' );

// Add Image upload and Color select to WordPress Theme Customizer.
require_once( get_stylesheet_directory() . '/lib/customize.php' );

// Include Customizer CSS.
include_once( get_stylesheet_directory() . '/lib/output.php' );

// Add WooCommerce support.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php' );

// Add the required WooCommerce styles and Customizer CSS.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php' );

// Add the Genesis Connect WooCommerce notice.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php' );

// Add the extended functions file  (th-- )
include_once( get_stylesheet_directory() . '/inc/functions-extended.php' );

// Add the 2nd extended functions file  (th-- )
include_once( get_stylesheet_directory() . '/inc/functions-extended-2.php' );

// Add WooCommerce Memberships modifications  (th-- )
include_once( get_stylesheet_directory() . '/inc/memberships-setup.php' );

// Enqueue style.css and minify, or enqueue style.min.css if available.  Cache-busting and enqueued at higher priority.
include_once( get_stylesheet_directory() . '/lib/main-style-min.php' );


// Change WooCommerce placeholder image
add_action( 'init', 'th_custom_fix_thumbnail' );
 
function th_custom_fix_thumbnail() {
  add_filter('woocommerce_placeholder_img_src', 'custom_woocommerce_placeholder_img_src');
   
	function custom_woocommerce_placeholder_img_src( $src ) {
	$upload_dir = wp_upload_dir();
	$uploads = untrailingslashit( $upload_dir['baseurl'] );
	$src = $uploads . '/2017/12/acmefx_social_media_sq.jpg';
	 
	return $src;
	}
}


// Remove admin bar from front end, except for select users
add_filter('show_admin_bar', 'th_private_admin_bar');  /** '__return_false' **/

function th_private_admin_bar( $content ) {

	$current_user = get_current_user_id();


	/**  User IDs:  Tomas = 11, Amy = 5, Kailey = 7;  UNCOMMENT TO ENABLE ADMIN BAR ON FRONT END     Note: there is no user 0.  *****/

	if ( $current_user == 0
		 ||	$current_user == 11 		// Tomas
		 || $current_user == 5 		// Amy
//		 || $current_user == 7 		// Kailey
								) {
	
		return $content;
	} else {
		return false;
	}
}


// Add custom links to admin bar
function th_add_admin_bar_links() {
	global $wp_admin_bar;
	$current_user = get_current_user_id();  // See "Remove admin bar from front end, except for select users" above for User IDs
//	if ( !is_super_admin() || !is_admin_bar_showing() ) return;
	if ( !( current_user_can( 'manage_options' ) || current_user_can( 'manage_woocommerce' ) ) || !is_admin_bar_showing() ) return;
	if ( $current_user == 11 || 1 == 1 ) {  // For Tomas OR turn on for all
		$wp_admin_bar->add_menu( array(
			'id' => 'credits_rd_desc_link', 
			'title' => __( 'Credits r.d.'), 
			'href' => esc_url( home_url( '/' ) ) . 'wp-admin/edit.php?post_type=credits&orderby=release_date&order=desc',
			)
		);
	}

}

add_action('wp_before_admin_bar_render', 'th_add_admin_bar_links', 12);


// Remove WP Migrate DB Pro, (CPT UI), except for Tomas, Amy
 add_action( 'admin_menu', 'th_remove_migrate_db_menu', 999 );
function th_remove_migrate_db_menu() {

$current_user = wp_get_current_user();
$current_username = $current_user->user_login;

	if ( $current_username !== 'tomas-acme-dev-admin' && $current_username !== 'Amy' ) {
		remove_submenu_page( 'tools.php', 'wp-migrate-db-pro' );
//		remove_menu_page( 'cptui_main_menu' );
	}
}


// Child theme (do not remove).
define( 'CHILD_THEME_NAME', 'Acme FX' );
define( 'CHILD_THEME_URL', 'https://parkdalewire.com/' );
define( 'CHILD_THEME_VERSION', '1.0.2' );


// Enqueue Scripts and Styles.
add_action( 'wp_enqueue_scripts', 'genesis_sample_enqueue_scripts_styles' );
function genesis_sample_enqueue_scripts_styles() {

	wp_enqueue_style( 'genesis-sample-fonts', '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700|Bitter:400,400i,700' );
	wp_enqueue_style( 'dashicons' );

	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css' );


	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	wp_enqueue_script( 'genesis-sample-headhesive', get_stylesheet_directory_uri() . '/js/headhesive.min.js', array( 'jquery' ), CHILD_THEME_VERSION );
    wp_enqueue_script( 'fitvids', get_stylesheet_directory_uri() . '/js/jquery.fitvids.js', array( 'jquery' ), CHILD_THEME_VERSION );
	wp_enqueue_script( 'genesis-sample-global', get_stylesheet_directory_uri() . '/js/global.js', array( 'jquery' ), CHILD_THEME_VERSION );

	wp_localize_script(
		'genesis-sample-responsive-menu',
		'genesis_responsive_menu',
		genesis_sample_responsive_menu_settings()
	);

}


//  Enqueue custom admin (and login) styles
function th_admin_theme_style() {
	// Get the stylesheet info.
	$stylesheet_uri = get_stylesheet_directory_uri() . '/th-admin-style.css';
	$stylesheet_dir = get_stylesheet_directory() . '/th-admin-style.css';
	$last_modified = date ( "Y-m-d_h.i.s", filemtime( $stylesheet_dir ) );
	// Enqueue the stylesheet.
	wp_enqueue_style( 'th-admin-style-versioned', $stylesheet_uri, array(), $last_modified );
}
add_action('admin_enqueue_scripts', 'th_admin_theme_style');
add_action('login_enqueue_scripts', 'th_admin_theme_style');


// Add categories to pages
function add_taxonomies_to_pages() {
// register_taxonomy_for_object_type( 'post_tag', 'page' );
 register_taxonomy_for_object_type( 'category', 'page' );
 }
add_action( 'init', 'add_taxonomies_to_pages' );


// Define responsive menu settings.
function genesis_sample_responsive_menu_settings() {

	$settings = array(
		'mainMenu'          => __( 'Menu', 'genesis-sample' ),
		'menuIconClass'     => 'dashicons-before dashicons-menu',
		'subMenu'           => __( 'Submenu', 'genesis-sample' ),
		'subMenuIconsClass' => 'dashicons-before dashicons-arrow-down-alt2',
		'menuClasses'       => array(
			'combine' => array(
				'.nav-primary',
				'.nav-header',
			),
			'others'  => array(),
		),
	);
	return $settings;
}


// Add HTML5 markup structure.
add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

// Add Accessibility support.
add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links' ) );

// Add viewport meta tag for mobile browsers.
add_theme_support( 'genesis-responsive-viewport' );

// Header Widgets, Custom Site Title Image, My Account link & icon
remove_action( 'genesis_header', 'genesis_do_header' ); 
add_action( 'genesis_header', 'genesis_do_new_header' ); 
function genesis_do_new_header() { 
    echo '<div class="title-area" id="title-area"><a href="' . site_url() . '"><img src="/wp-content/themes/acme-fx/images/acme-logo.svg" alt="Site Logo" />'; 
    //do_action( 'genesis_site_title' ); 
    do_action( 'genesis_site_description' ); 
    echo '</a></div><!-- end #title-area -->'; 


    if ( is_active_sidebar( 'header-right' ) || has_action( 'genesis_header_right' ) ) { 
        echo '<div class="widget-area header-widget-area">'; 
        do_action( 'genesis_header_right' ); 
        dynamic_sidebar( 'header-right' ); 
        echo '</div><!-- end .widget-area -->';

    }
    /** Turned on ( 1 == 1 ||) to activate widget area so that My Account icon appears */
    if ( 1 == 1 || is_active_sidebar( 'header-left' ) || has_action( 'genesis_header_left' ) ) { 
        echo '<div class="widget-area header-widget-area-2">'; 
        do_action( 'genesis_header_left' ); 
        dynamic_sidebar( 'header-left' ); 
        echo  '<a href="' . esc_url( home_url( '/' ) ) . 'my-account/">
<svg class="header-profile" viewBox="0 0 100 100" fill="#AE4040"><style type="text/css">  
			.st0{fill:#AE4040;}
		</style><foreignObject requiredExtensions="http://ns.adobe.com/AdobeIllustrator/10.0/" width="1" height="1"/><path class="st0" d="M50 51.7c12.2 0 22.1-9.9 22.1-22.1S62.2 7.5 50 7.5s-22.1 9.9-22.1 22.1S37.8 51.7 50 51.7z"/><path class="st0" d="M87.9 69.3c-0.6-1.4-1.4-2.8-2.2-4.1 -4.4-6.5-11.3-10.9-19-12 -1-0.1-2 0.1-2.8 0.7 -4 2.9-8.8 4.5-13.8 4.5s-9.8-1.6-13.8-4.5c-0.8-0.6-1.8-0.8-2.8-0.7 -7.7 1.1-14.6 5.4-19 12 -0.9 1.3-1.6 2.7-2.2 4.1 -0.3 0.6-0.2 1.3 0.1 1.8 0.8 1.3 1.7 2.7 2.6 3.9 1.3 1.8 2.8 3.5 4.4 5 1.4 1.4 2.9 2.6 4.5 3.8 7.6 5.7 16.7 8.7 26.2 8.7s18.6-3 26.2-8.7c1.6-1.2 3.1-2.5 4.5-3.8 1.6-1.5 3.1-3.2 4.4-5 0.9-1.2 1.8-2.5 2.6-3.9C88.1 70.5 88.1 69.9 87.9 69.3z"/></svg>
</a>';
        echo '</div><!-- end .widget-area -->';
    }
} 


// Add support for custom header.
add_theme_support( 'custom-header', array(
	'width'           => 1920,
	'height'          => 400,
	'header-selector' => '.site-title a',
	'header-text'     => true,
	'flex-width'     => true,
	'flex-height'     => true,
	//'video' => true,
) );

//  Video header settings
add_filter( 'header_video_settings', 'th_header_video_settings');
function th_header_video_settings( $settings ) {
  $settings['minWidth'] = 1025;  // minimum VIEWPORT width for video to play (320) (iPad = 768)
  $settings['minHeight'] = 600;  // minimum VIEWPORT height for video to play (568) (iPad = 1024)
  $settings['width'] = 1920;  // video width
  $settings['height'] = 400;  // video height
//  $settings['posterUrl'] = get_header_image();
  return $settings;
}

// Add support for video header
add_action( 'genesis_header', 'the_custom_header_markup' );

// Add support for custom background.
add_theme_support( 'custom-background' );

//* Remove the edit link
add_filter ( 'genesis_edit_post_link' , '__return_false' );

// Add support for after entry widget.
add_theme_support( 'genesis-after-entry-widget-area' );

// Add support for 3-column footer widgets.
add_theme_support( 'genesis-footer-widgets', 3 );

// Turn off Wordpress native jpeg compression if Smush Pro is active
add_filter( 'jpeg_quality', 'th_jpeg_100' );
function th_jpeg_100() {
	if ( is_plugin_active( 'wp-smush-pro/wp-smush.php' ) ) {
		return 100;
	}
	return 90;
}

// Add Image Sizes.
add_image_size( 'featured-image', 720, 400, TRUE );
add_image_size( 'credit-poster', 190, 9999 );


// Modify size of the Gravatar in the author box.
add_filter( 'genesis_author_box_gravatar_size', 'genesis_sample_author_box_gravatar' );
function genesis_sample_author_box_gravatar( $size ) {
	return 90;
}

// Modify size of the Gravatar in the entry comments.
add_filter( 'genesis_comment_list_args', 'genesis_sample_comments_gravatar' );
function genesis_sample_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;

	return $args;

}

//add_filter( 'header_video_settings', 'my_header_video_settings');
function my_header_video_settings( $settings ) {
  $settings['l10n'] = array(
    'pause'      => __( '<span class="dashicons dashicons-controls-pause"></span>' ),
    'play'       => __( '<span class="dashicons dashicons-controls-play"></span>' ),
    'pauseSpeak' => __( 'Video stopped.'),
    'playSpeak'  => __( 'Video started.'),
  );
  return $settings;
}


remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_after_header', 'genesis_do_nav', 5 );


// th-- Set up the Front page. *******************************
// Setup widget counts.
function acme_count_widgets( $id ) {
	global $sidebars_widgets;

	if ( isset( $sidebars_widgets[ $id ] ) ) {
		return count( $sidebars_widgets[ $id ] );
	}
}


// Setup widget layout classes
function acme_widget_area_class( $id ) {
	$count = acme_count_widgets( $id );

	$class = '';

	if( $count == 1 ) {
		$class .= ' widget-full';
	} elseif( $count % 3 == 0 ) {
		$class .= ' widget-thirds';
	} elseif( $count % 4 == 0 ) {
		$class .= ' widget-fourths';
	} elseif( $count % 2 == 1 ) {
		$class .= ' widget-halves uneven';
	} else {
		$class .= ' widget-halves';
	}

	return $class;
}
// Add header left widget
genesis_register_sidebar( array(
	'id'          => 'header-left',
	'name'        => __( 'Header-Left', 'genesis-sample' ),
	'description' => __( 'This widget goes in the left of the header.', 'genesis-sample' ),
));
// Add Front Page Template widget areas.
genesis_register_sidebar( array(
	'id'          => 'front-page-1',
	'name'        => __( 'Front Page 1', 'genesis-sample' ),
	'description' => __( 'The first section on the front page.', 'genesis-sample' ),
));
genesis_register_sidebar( array(
	'id'          => 'front-page-2',
	'name'        => __( 'Front Page 2', 'genesis-sample' ),
	'description' => __( 'The second section on the front page.', 'genesis-sample' ),
));
genesis_register_sidebar( array(
	'id'          => 'front-page-3-a',
	'name'        => __( 'Front Page 3 - Top', 'genesis-sample' ),
	'description' => __( 'The top half of the third section on the front page.', 'genesis-sample' ),
));
genesis_register_sidebar( array(
	'id'          => 'front-page-3-b',
	'name'        => __( 'Front Page 3 - Bottom', 'genesis-sample' ),
	'description' => __( 'The bottom half of the third section on the front page.', 'genesis-sample' ),
));
genesis_register_sidebar( array(
	'id'          => 'front-page-4',
	'name'        => __( 'Front Page 4', 'genesis-sample' ),
	'description' => __( 'The fourth section on the front page.', 'genesis-sample' ),
));
genesis_register_sidebar( array(
	'id'          => 'front-page-5',
	'name'        => __( 'Front Page 5', 'genesis-sample' ),
	'description' => __( 'The fifth section on the front page.', 'genesis-sample' ),
));
genesis_register_sidebar( array(
	'id'          => 'footer-banner',
	'name'        => __( 'Footer Banner', 'genesis-sample' ),
	'description' => __( 'A sitewide section just above the footer section.', 'genesis-sample' ),
));
genesis_register_sidebar( array(
	'id'          => 'footer-widgets',
	'name'        => __( 'Footer Widgets', 'genesis-sample' ),
	'description' => __( 'This is the footer section.', 'genesis-sample' ),
));


//* Customize the entire footer
remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'th_custom_footer' );
function th_custom_footer() {
	?>
	<p>Copyright &copy; <?php echo date('Y'); ?> <a href="<?php echo esc_url( home_url( '/' )); ?>">Acme FX</a></p><span class="footer-middot">&nbsp; &middot; &nbsp;</span><p>Built by <a href="https://parkdalewire.com" target="_blank">Parkdale Wire</a></p>
	<?php
}

//**  th-- WP Maintenance Mode plugin styles -- INACTIVE
function th_mm_css_styles($styles) {
    $styles['new-style'] = get_stylesheet_directory_uri() . '/style-mm.css'; // replace with the real path...

    return $styles;
}
// add_filter('wpmm_styles', 'th_mm_css_styles');


//************** Place the Genesis Simple Share buttons below content for single products 
add_action( 'woocommerce_after_single_product_summary', 'acme_entry_share', 8 );
/**
 * Adds the Genesis Share icons before the entry.
 *
 * @since 1.0.0
 *
 * @return null Return early for non-single posts
 */
function acme_entry_share() {
	if ( ! is_single() || ! function_exists( 'genesis_share_icon_output' ) ) {
		return;
	}
	
	global $Genesis_Simple_Share;
		 
	echo '<div class="share-box">';
	genesis_share_icon_output( 'after_content', $Genesis_Simple_Share->icons );
	echo '</div>';
}


//************** Place the Genesis Simple Share buttons above content in single entries 
add_action( 'genesis_entry_footer', 'acme_suffix_entry', 20 );
/**
 * Adds the Genesis Share icons after the entry.
 *
 * @since 1.0.0
 *
 * @return null Return early for non-single posts
 */
function acme_suffix_entry() {
	if ( ! is_single() || ! function_exists( 'genesis_share_icon_output' ) ) {
		return;
	}
	
	global $Genesis_Simple_Share;
		 
	echo '<div class="share-box"><h3 class="share-headline">' . __( 'If you liked this article, tell someone about it', 'yourtextdomain' ) . '</h3>';
	genesis_share_icon_output( 'after_content', $Genesis_Simple_Share->icons );
	echo '</div>';
}

//* Customize the post info function
add_filter( 'genesis_post_info', 'sp_post_info_filter' );
function sp_post_info_filter($post_info) {
if ( !is_page() ) {
	$post_info = '[post_date] by [post_author_posts_link]';
	return $post_info;
}}


/**
 * Customize Read More Link
 * @author Bill Erickson
 * @link http://www.billerickson.net/read-more-link
 *
 * @param string
 * @return string
 */
function be_more_link($more_link) {
	return sprintf('... <a href="%s" class="more-link">%s</a>', get_permalink(), '&nbsp;Read more');
}
add_filter( 'excerpt_more', 'be_more_link' );
add_filter( 'get_the_content_more_link', 'be_more_link' );
add_filter( 'the_content_more_link', 'be_more_link' );



// ******************************  WOOCOMMERCE  **************************************** //
// ************************************************************************************* //

/** Disable Ajax call from WooCommerce, except: woocommerce pages etc. AND pages with sidebars (mini-cart) */
add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_cart_fragments', 1001); 
function dequeue_woocommerce_cart_fragments() {

	$site_layout = genesis_site_layout();

	if ( is_shop() || is_cart() || is_checkout() || is_account_page() || is_woocommerce() || 'full-width-content' !== $site_layout ) {
		return;
	} else {
		wp_dequeue_script( 'wc-cart-fragments' );
	}
}


//  Enable woocommerce product gallery zoom, lightbox, slider
add_action( 'after_setup_theme', 'acme_woo_gallery_setup' );
 
function acme_woo_gallery_setup() {
//    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}

// Remove the sorting dropdown from Woocommerce
remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_catalog_ordering', 30 );
// Remove the result count from WooCommerce
// remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_result_count', 20 );

// Remove page title from woocommerce archives
add_filter( 'woocommerce_show_page_title', 'th_no_page_title_on_woo_archives' );
function th_no_page_title_on_woo_archives($state) {
	if ( ( is_product_category() || is_product_tag() || is_archive() ) && !is_shop() ) {
		return false;
	} else {
		return true;
	}
}

// Remove the product rating display on product loops
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );



// *
// ** Add rental pricing fields to the admin product page AND update meta
// *

add_action( 'woocommerce_product_options_general_product_data', 'wc_custom_add_custom_fields' );
function wc_custom_add_custom_fields() {
    // Day rental number field
    woocommerce_wp_text_input( array(
        'id' => '_day_rental_price',
        'label' => 'Daily rental price',
        'description' => 'Price for 1-day rental.<br>Rental prices are used when the "Departments" attribute is set to Rentals.',
        'desc_tip' => 'true',
        'placeholder' => ''
    ) );
    // Week rental number field
    woocommerce_wp_text_input( array(
        'id' => '_week_rental_price',
        'label' => 'Weekly rental price',
        'description' => 'Price for 1-week rental.<br>Rental prices are used when the "Departments" attribute is set to Rentals.',
        'desc_tip' => 'true',
        'placeholder' => ''
    ) );
    // Month rental number field
    woocommerce_wp_text_input( array(
        'id' => '_month_rental_price',
        'label' => 'Monthly rental price',
        'description' => 'Price for 1-month rental.<br>Rental prices are used when the "Departments" attribute is set to Rentals.',
        'desc_tip' => 'true',
        'placeholder' => ''
    ) );
}
add_action( 'woocommerce_process_product_meta', 'pw_custom_save_custom_fields' );
function pw_custom_save_custom_fields( $post_id ) {
	// Update Daily rental price meta
        update_post_meta( $post_id, '_day_rental_price', esc_attr( $_POST['_day_rental_price'] ) );

	// Update Weekly rental price meta
        update_post_meta( $post_id, '_week_rental_price', esc_attr( $_POST['_week_rental_price'] ) );

	// Update Monthly rental price meta
        update_post_meta( $post_id, '_month_rental_price', esc_attr( $_POST['_month_rental_price'] ) );

}


// remove "Add to cart" and Quantity field from pages with product attribute Departments set to Rentals, by way of CSS (wrap in div.no-price)
add_action( 'woocommerce_single_product_summary', 'th_remove_price_and_quantity', 1 );
function th_remove_price_and_quantity() {
	global $product;
	$departments = $product->get_attribute( 'pa_departments' );
	$is_rental = strpos($departments, 'Rentals' ) !== false ? true : false;
		if( $is_rental ) {

			add_action( 'woocommerce_before_add_to_cart_button', 'th_div_to_hide_price_and_quantity', 5 );
			function th_div_to_hide_price_and_quantity() {
				echo '<div class="no-price">';

			}

			add_action( 'woocommerce_after_add_to_cart_button', 'th_div_to_hide_price_and_quantity_end', 250 );
			function th_div_to_hide_price_and_quantity_end() {
				echo '</div><!-- .no-price -->';
			}
		}

}


// ********************************************************************************************  HOOK DEACTIVATED TO HIDE PRICING
// Display rental pricing table on a single product page -- when product attribute Departments is set to Rentals
add_action( 'woocommerce_single_product_summary', 'th_add_price_table', 25 );
function th_add_price_table() {
	$daily_rental_price = esc_html( get_post_meta( get_the_ID(), '_day_rental_price', true ) );
	$weekly_rental_price = esc_html( get_post_meta( get_the_ID(), '_week_rental_price', true ) );
	$monthly_rental_price = esc_html( get_post_meta( get_the_ID(), '_month_rental_price', true ) );

			global $product;
			$departments = $product->get_attribute( 'pa_departments' );

			if ( is_product() && ( strpos( $departments, 'Rentals' ) !== false ) ) { ?>

				<h4 class="pricing-table-heading">Rental prices</h4>
				<table class="pricing-table">
					<tbody>

						<?php if( $daily_rental_price ) : ?>
									<tr class="border-row">
										<th>
											<em>Daily</em>
										</th>
										<td>
											<?php echo '$' . $daily_rental_price; ?>
										</td>
									</tr>
						<?php endif ?>

						<?php if( $weekly_rental_price ) : ?>
									<tr class="<?php if( !$daily_rental_price>0 || $monthly_rental_price>0 ) {echo "border-row";} ?>">
										<th>
											<em>Weekly</em>
										</th>
										<td>
											<?php echo '$' . $weekly_rental_price; ?>
										</td>
									</tr>
						<?php endif ?>

						<?php if( $monthly_rental_price ) : ?>
									<tr class="<?php if( !$daily_rental_price>0 && !$weekly_rental_price>0 ) {echo "border-row";} ?>">
										<th>
											<em>Monthly</em>
										</th>
										<td>
											<?php echo '$' . $monthly_rental_price; ?>
										</td>
									</tr>
						<?php endif ?>

					</tbody>
				</table>

	<?php }
}

// *
// ** END ::  Add rental pricing fields and display them
// *



// Changes empty price to "Rental item" on product loops and also in admin - Products -- when product attribute Departments is set to Rentals
add_filter( 'woocommerce_get_price_html', 'th_change_product_price_display', 10, 2 );
function th_change_product_price_display( $price, $product ) {

	$departments = $product->get_attribute( 'pa_departments' );

		if ( $product && ( strpos( $departments, 'Rentals' ) !== false ) ) {
			$price = 'Rental item';
		}
	return $price;
}


// Change the add to cart button INTO "Read more" button on product archive pages, if pa_departments == 'Rentals'
// ====
    // Woocommerce handles empty Regular price the same way, a zero price not, though.  This is insurance.
	// Priority 100 hooks this after Catalog VisibiLity Options (99), which also, uses "Read more" replacement.  Reverse order doubles buttons...
add_filter( 'woocommerce_loop_add_to_cart_link', 'pw_product_link_to_view', 100, 2 );
function pw_product_link_to_view( $markup, $product ) {

	$departments = $product->get_attribute( 'pa_departments' );
	$is_rental = strpos( $departments, 'Rentals' ) !== false ? true : false;

		if ( $product && $is_rental ) {
			$id = $product->get_id();
		    echo '<form action="' . esc_url( $product->get_permalink( $id ) ) . '" method="get">
		            <button type="submit" class="button add_to_cart_button ">' . 'Read more' . '</button>
		          </form>';
		} else {
			return $markup;
		}
}

//  Move Cross-sells to after Cart Totals
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 20 );

//  4 columns for cross-sells
add_filter( 'woocommerce_cross_sells_columns', 'th_change_cross_sells_columns' );
function th_change_cross_sells_columns( $columns ) {
	return 4;
}

//  Remove "Reviews" tab from single product page
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {

    unset( $tabs['reviews'] );

    return $tabs;
}


// Search button icon
add_filter( 'genesis_search_button_text', 'b3m_search_button_dashicon' );
function b3m_search_button_dashicon( $text ) {
	
	return esc_attr( '&#xf179;' );
}


// Woocommerce search - search icon + 
add_filter( 'get_product_search_form' , 'woo_custom_product_searchform' );
/**
 * woo_custom_product_searchform
 *
 * @access      public
 * @since       1.0 
 * @return      void
*/
function woo_custom_product_searchform( $form ) {
	//  a div was removed (inside form)
		//  To turn on live search for WC Product search (may return site-wide results throught Ajax):
		//  use this:
		//  data-swplive="true" /> <!-- data-swplive="true" enables SearchWP Live Search -->
		//  to replace this (at the end of <input type="text" line):
		//  />
	$form = '<form class="woocommerce-product-search" role="search" method="get" id="searchform" action="' . esc_url( home_url( '/' ) ) . '">
		
			<label class="screen-reader-text" for="s">' . __( 'Search for:', 'woocommerce' ) . '</label>
			<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . __( 'Search Products', 'woocommerce' ) . '" />
			<input type="submit" id="searchsubmit" value="'. esc_attr__( '&#xf179;', 'woocommerce' ) .'" />
			<input type="hidden" name="post_type" value="product" />
		
	</form>';
	
	return $form;
	
}


/**
 * Limit Primary Menu to Top Level Items
 * th-- or one level drop-down...
 * @author Bill Erickson
 * @link http://www.billerickson.net/customizing-menu-arguments/
 * 
 * @param array @args
 * @return array
 *
 */
function be_primary_menu_args( $args ) {
  if( 'primary' == $args['theme_location'] || true ) {
    $args['depth'] = 2;
  }
  
  return $args;
}
add_filter( 'wp_nav_menu_args', 'be_primary_menu_args' );


/**
 * Disable REST API for non-logged-in users. Used to exclude Woo Memberships restricted content from REST API.
 */
/** TODO: This breaks WP Engine Content Performance's connection.  Filter by request type or such... or maybe there is a DB entry for Woo protected content to look for? */
//add_filter( 'rest_authentication_errors', function( $result ) {
//	if ( ! empty( $result ) ) {
//		return $result;
//	}
//	if ( ! is_user_logged_in() ) {
//		return new WP_Error( 'rest_not_logged_in', 'You are not currently logged in.', array( 'status' => 401 ) );
//	}
//	return $result;
//});

// Turn off SearchWP woocommerce nag
add_filter( 'searchwp_missing_integration_notices', '__return_false' );


add_filter( 'wp_default_scripts', 'ea_dequeue_jquery_migrate' );
/**
 * Dequeue jQuery Migrate
 *
 * From: EA-Genesis-Child-master
 */
function ea_dequeue_jquery_migrate( &$scripts ){
	if( !is_admin() ) {
		$scripts->remove( 'jquery');
		$scripts->add( 'jquery', false, array( 'jquery-core' ), '1.10.2' );
	}
}
