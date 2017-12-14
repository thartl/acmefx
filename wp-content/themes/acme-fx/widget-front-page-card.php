<?php

$current_acfw_widget_base = 'widget_' . $widget_id . '_';

$title_field = esc_html( get_option( $current_acfw_widget_base . 'title' ) );

$intro_text = get_option( $current_acfw_widget_base . 'intro_text' );

$page_url = esc_url( get_option( $current_acfw_widget_base . 'link_to_page' ) );
//$page_url = esc_url( get_page_link( $page_id ) );

//			$url = esc_url( get_post_meta( get_the_ID(), 'imdb_link', true ) );


$image = get_option( $current_acfw_widget_base . 'bg_image' );
$image_array = $image ? wp_get_attachment_image_src( $image, 'medium_large', false ) : '';
$image_url = $image_array[ 0 ];
// $image_width_half = round( $image_array[ 1 ] / 2 );
// $image_height_half = round( $image_array[ 2 ] / 2 );

$icon_url = esc_url( get_option( $current_acfw_widget_base . 'icon_url' ) );
$icon_img = '<img src="' . $icon_url . '" class="fp-card-icon" >';


?>


<div class="fp-card-widget">
	<div class="fp-widget-bg" style="background-image: url('<?php echo $image_url; ?>');"></div>

	<a class="overlay" href="<?php echo $page_url; ?>" ></a>

	<a href="<?php echo $page_url; ?>" >
		<h2><?php echo $title_field; ?></h2>
		<?php if( ! empty( $icon_url ) ) {
					echo $icon_img;
					echo '<p class="allow-icon">' . $intro_text . '</p>';
				} else {
					echo '<p>' . $intro_text . '</p>';
				} ?>
	</a>
</div>