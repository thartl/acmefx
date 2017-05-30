<?php
/**
 * Acme FX.
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

// Child theme (do not remove).
define( 'CHILD_THEME_NAME', 'Acme FX' );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/' );
define( 'CHILD_THEME_VERSION', '1.0.1' );



// Enqueue Scripts and Styles.
add_action( 'wp_enqueue_scripts', 'genesis_sample_enqueue_scripts_styles' );
function genesis_sample_enqueue_scripts_styles() {

	wp_enqueue_style( 'genesis-sample-fonts', '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700|Bitter:400,400i,700', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'dashicons' );

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	wp_enqueue_script( 'genesis-sample-global', get_stylesheet_directory_uri() . '/js/global.js', array( 'jquery' ), CHILD_THEME_VERSION );
	wp_enqueue_script( 'genesis-sample-headhesive', get_stylesheet_directory_uri() . '/js/headhesive.min.js', array( 'jquery' ), CHILD_THEME_VERSION );

	wp_localize_script(
		'genesis-sample-responsive-menu',
		'genesis_responsive_menu',
		genesis_sample_responsive_menu_settings()
	);

}


// Define our responsive menu settings.
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

// Header Widgets and Custom Site Title Image

remove_action( 'genesis_header', 'genesis_do_header' ); 
add_action( 'genesis_header', 'genesis_do_new_header' ); 
function genesis_do_new_header() { 
    echo '<div class="title-area" id="title-area"><a href="' . site_url() . '"><img src="/wp-content/themes/acme-fx/images/acme-logo-orig-traced.svg" alt="Site Logo" />'; 
    //do_action( 'genesis_site_title' ); 
    do_action( 'genesis_site_description' ); 
    echo '</a></div><!-- end #title-area -->'; 


    if ( is_active_sidebar( 'header-right' ) || has_action( 'genesis_header_right' ) ) { 
        echo '<div class="widget-area header-widget-area">'; 
        do_action( 'genesis_header_right' ); 
        dynamic_sidebar( 'header-right' ); 
        echo '</div><!-- end .widget-area -->';

    }
    if ( is_active_sidebar( 'header-left' ) || has_action( 'genesis_header_left' ) ) { 
        echo '<div class="widget-area header-widget-area-2">'; 
        do_action( 'genesis_header_left' ); 
        dynamic_sidebar( 'header-left' ); 
        echo '</div><!-- end .widget-area -->';
    }
} 

// Add support for custom header.
add_theme_support( 'custom-header', array(
	'width'           => 1920,
	'height'          => 600,
	'header-selector' => '.site-title a',
	'header-text'     => true,
	'flex-width'     => true,
	'flex-height'     => true,
	'video' => true,
) );

add_action( 'genesis_header', 'the_custom_header_markup' );

// Add support for custom background.
add_theme_support( 'custom-background' );

// Add support for after entry widget.
add_theme_support( 'genesis-after-entry-widget-area' );

// Add support for 3-column footer widgets.
add_theme_support( 'genesis-footer-widgets', 3 );

// Add Image Sizes.
add_image_size( 'featured-image', 720, 400, TRUE );

// Rename primary and secondary navigation menus.
// add_theme_support( 'genesis-menus', array( 'primary' => __( 'After Header Menu', 'genesis-sample' ), 'secondary' => __( 'Footer Menu', 'genesis-sample' ) ) );

// Reposition the secondary navigation menu.
// remove_action( 'genesis_after_header', 'genesis_do_subnav' );
// add_action( 'genesis_footer', 'genesis_do_subnav', 5 );

// Reduce the secondary navigation menu to one level depth.
add_filter( 'wp_nav_menu_args', 'genesis_sample_secondary_menu_args' );
function genesis_sample_secondary_menu_args( $args ) {
// add_filter( 'wp_nav_menu_args', 'genesis_sample_secondary_menu_args' );
// function genesis_sample_secondary_menu_args( $args ) {

	if ( 'secondary' != $args['theme_location'] ) {
		return $args;
	}
// 	if ( 'secondary' != $args['theme_location'] ) {
// 		return $args;
// 	}

	$args['depth'] = 1;
// 	$args['depth'] = 1;

	return $args;
// 	return $args;

}
// }

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
add_filter( 'header_video_settings', 'my_header_video_settings');
function my_header_video_settings( $settings ) {
  $settings['l10n'] = array(
    'pause'      => __( '<span class="dashicons dashicons-controls-pause"></span>' ),
    'play'       => __( '<span class="dashicons dashicons-controls-play"></span>' ),
    'pauseSpeak' => __( 'Video stopped.'),
    'playSpeak'  => __( 'Video started.'),
  );
  return $settings;
}

//* Reposition the primary navigation menu
// remove_action( 'genesis_after_header', 'genesis_do_subnav' );
// add_action( 'genesis_header', 'genesis_do_subnav' );

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
	<p>Copyright &copy; <?php echo date('Y'); ?> &middot; <a href="http://acmefx.wpengine.com/">Acme FX</a> &middot; <?php echo do_shortcode( '[footer_loginout]' ); ?></p>
	<?php
}


//* Place the Genesis Simple Share buttons below content for single products 
add_action( 'woocommerce_share', 'acme_entry_share' );
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

//* Place the Genesis Simple Share buttons above content in single entries 
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

//  th-- enamble woocommerce product gallery + lightbox, slider
add_action( 'after_setup_theme', 'acme_woo_gallery_setup' );
 
function acme_woo_gallery_setup() {
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}

// Remove the sorting dropdown from Woocommerce
remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_catalog_ordering', 30 );
// Remove the result count from WooCommerce
remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_result_count', 20 );

// Remove all woocommerce stylesheets
// add_filter( 'woocommerce_enqueue_styles', '__return_false' );

// Remove page title from woocommerce archives
add_filter( 'woocommerce_show_page_title', 'th_no_page_title_on_woo_archives' );
function th_no_page_title_on_woo_archives($state) {
	if ( is_product_category() && !is_shop() ) {
		return false;
	} else {
		return true;
	}
}



