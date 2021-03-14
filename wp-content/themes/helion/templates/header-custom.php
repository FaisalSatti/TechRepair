<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0.06
 */

$helion_header_css   = '';
$helion_header_image = get_header_image();
$helion_header_video = helion_get_header_video();
if ( ! empty( $helion_header_image ) && helion_trx_addons_featured_image_override( is_singular() || helion_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$helion_header_image = helion_get_current_mode_image( $helion_header_image );
}

$helion_header_id = helion_get_custom_header_id();
$helion_header_meta = get_post_meta( $helion_header_id, 'trx_addons_options', true );
if ( ! empty( $helion_header_meta['margin'] ) ) {
	helion_add_inline_css( sprintf( '.page_content_wrap{padding-top:%s}', esc_attr( helion_prepare_css_value( $helion_header_meta['margin'] ) ) ) );
}

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr( $helion_header_id ); ?> top_panel_custom_<?php echo esc_attr( sanitize_title( get_the_title( $helion_header_id ) ) ); ?>
				<?php
				echo ! empty( $helion_header_image ) || ! empty( $helion_header_video )
					? ' with_bg_image'
					: ' without_bg_image';
				if ( '' != $helion_header_video ) {
					echo ' with_bg_video';
				}
				if ( '' != $helion_header_image ) {
					echo ' ' . esc_attr( helion_add_inline_css_class( 'background-image: url(' . esc_url( $helion_header_image ) . ');' ) );
				}
				if ( is_single() && has_post_thumbnail() ) {
					echo ' with_featured_image';
				}
				if ( helion_is_on( helion_get_theme_option( 'header_fullheight' ) ) ) {
					echo ' header_fullheight helion-full-height';
				}
				$helion_header_scheme = helion_get_theme_option( 'header_scheme' );
				if ( ! empty( $helion_header_scheme ) && ! helion_is_inherit( $helion_header_scheme  ) ) {
					echo ' scheme_' . esc_attr( $helion_header_scheme );
				}

				?>
">
	<?php

	// Background video
	if ( ! empty( $helion_header_video ) ) {
		get_template_part( apply_filters( 'helion_filter_get_template_part', 'templates/header-video' ) );
	}

	// Custom header's layout
	do_action( 'helion_action_show_layout', $helion_header_id );

	// Header widgets area
	get_template_part( apply_filters( 'helion_filter_get_template_part', 'templates/header-widgets' ) );

	?>
</header>
