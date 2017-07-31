<?php

$current_acfw_widget_base = 'widget_' . $widget_id . '_';

$title_field = esc_html( get_option( $current_acfw_widget_base . 'title' ) );

$intro_text = get_option( $current_acfw_widget_base . 'intro_text' );

$page_url = esc_url( get_option( $current_acfw_widget_base . 'link_to_page' ) );
//$page_url = esc_url( get_page_link( $page_id ) );

//			$url = esc_url( get_post_meta( get_the_ID(), 'imdb_link', true ) );


$image = get_option( $current_acfw_widget_base . 'bg_image' );
$image_array = $image ? wp_get_attachment_image_src( $image, 'full', false, array( 'class' => 'fx-widget-bg', 'alt' => $title_field, ) ) : '';
$image_url = $image_array[ 0 ];
// $image_width_half = round( $image_array[ 1 ] / 2 );
// $image_height_half = round( $image_array[ 2 ] / 2 );

$icon_url = esc_url( get_option( $current_acfw_widget_base . 'icon_url' ) );
$icon_img = '<img src="' . $icon_url . '" class="fp-card-icon" >';


?><div class="fp-card-widget" style="background-image: url('<?php echo $image_url; ?>');">
	<div class="fp-card-overlay match-height-item">
	<a href="<?php echo $page_url; ?>" class="taphover" >
		<h2><?php echo $title_field; ?></h2>
		<?php if( ! empty( $icon_url ) ) {
					echo $icon_img;
					echo '<p class="allow-icon">' . $intro_text . '</p>';
				} else {
					echo '<p>' . $intro_text . '</p>';
				} ?>
	</a>
</div>
</div>