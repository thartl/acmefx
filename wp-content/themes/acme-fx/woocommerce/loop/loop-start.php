<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
<<<<<<< HEAD
 * @version     2.0.0
 */

=======
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
>>>>>>> 2e87e513cbc1cfae91d0ba58819db76ef4157f15


if( !isset($_COOKIE['store_view']) || $_COOKIE['store_view'] == 'grid' ) {

<<<<<<< HEAD
	echo '<ul class="products grid-view" >';

} elseif( $_COOKIE['store_view'] == 'list' ) {

	echo '<ul class="products list-view" >';

} elseif( $_COOKIE['store_view'] == 'desc' ) {

	echo '<ul class="products desc-view" >';

} else {

	echo '<ul class="products" >';

}


?>
=======
$add_view_class = 'grid-view';

} elseif( $_COOKIE['store_view'] == 'list' ) {

$add_view_class = 'list-view';

} elseif( $_COOKIE['store_view'] == 'desc' ) {

$add_view_class = 'desc-view';

} else {

$add_view_class = '';

}
?>

<ul class="products columns-<?php echo esc_attr( wc_get_loop_prop( 'columns' ) ); ?> <?php echo $add_view_class; ?>" >
>>>>>>> 2e87e513cbc1cfae91d0ba58819db76ef4157f15
