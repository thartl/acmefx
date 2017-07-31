<?php

$current_acfw_widget_base = 'widget_' . $widget_id . '_';

$title_field = esc_html( get_option( $current_acfw_widget_base . 'title' ) );

$intro_text = get_option( $current_acfw_widget_base . 'intro_text' );

$page_id = (int) get_option( $current_acfw_widget_base . 'link_to_page' );
$page_url = esc_url( get_page_link( $page_id ) );

$image = get_option( $current_acfw_widget_base . 'bg_image' );
$image_url = $image ? wp_get_attachment_image( $image, 'full', false, array( 'class' => 'fx-widget-bg', 'alt' => $title_field, ) ) : '';

$icon_url = esc_url( get_option( $current_acfw_widget_base . 'icon_url' ) );

// class="taphover" (re-attach to <a>)

?><div class="fx-type-widget" >
	<a href="<?php echo $page_url; ?>" >
		<?php echo $image_url; ?>
		<img src="<?php echo $icon_url; ?>" class="fx-icon" >
		<h2><?php echo $title_field; ?></h2>
		<p><?php echo $intro_text; ?></p>
	</a>
</div>