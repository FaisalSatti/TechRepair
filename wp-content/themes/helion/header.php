<?php
/**
 * The Header: Logo and main menu
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js
									<?php
										// Class scheme_xxx need in the <html> as context for the <body>!
										echo ' scheme_' . esc_attr( helion_get_theme_option( 'color_scheme' ) );
									?>
										">
<head>
	<?php wp_head(); ?>
</head>

<body <?php	body_class(); ?>>

    <?php
    if ( function_exists( 'wp_body_open' ) ) {
        wp_body_open();
    } else {
        do_action( 'wp_body_open' );
    }
        do_action( 'helion_action_before_body' );
    ?>

	<div class="body_wrap">

		<div class="page_wrap<?php $body_bg_image = helion_get_theme_option( 'body_bg_image' );
                                   $body_bg_image_switch_on =  helion_is_on( helion_get_theme_option( 'body_bg_image_switch' ) );

                                    if ( $body_bg_image_switch_on ) {
                                        echo ' with_background';
                                    }
                                    if  ( $body_bg_image_switch_on && !empty ( $body_bg_image ) ) {
                                        echo ' ' . esc_attr( helion_add_inline_css_class( 'background-image: url(' . esc_url( $body_bg_image ) . ') !important;' ) );
                                    }
                             ?>">
			<?php

			// Short links to fast access to the content, sidebar and footer from the keyboard
			?>
			<a class="helion_skip_link skip_to_content_link" href="#content_skip_link_anchor" tabindex="1"><?php esc_html_e( "Skip to content", 'helion' ); ?></a>
			<?php if ( helion_sidebar_present() ) { ?>
			<a class="helion_skip_link skip_to_sidebar_link" href="#sidebar_skip_link_anchor" tabindex="1"><?php esc_html_e( "Skip to sidebar", 'helion' ); ?></a>
			<?php } ?>
			<a class="helion_skip_link skip_to_footer_link" href="#footer_skip_link_anchor" tabindex="1"><?php esc_html_e( "Skip to footer", 'helion' ); ?></a>
			
			<?php
			// Header
			$helion_header_type = helion_get_theme_option( 'header_type' );
			if ( 'custom' == $helion_header_type && ! helion_is_layouts_available() ) {
				$helion_header_type = 'default';
			}
			get_template_part( apply_filters( 'helion_filter_get_template_part', "templates/header-{$helion_header_type}" ) );

			// Side menu
			if ( in_array( helion_get_theme_option( 'menu_style' ), array( 'left', 'right', 'right_anchors' ) ) ) {
				get_template_part( apply_filters( 'helion_filter_get_template_part', 'templates/header-navi-side' ) );
			}
            // Mobile menu
            get_template_part(apply_filters('helion_filter_get_template_part', 'templates/header-navi-mobile'));

			// Single posts banner after header
			helion_show_post_banner( 'header' );
			?>

			<div class="page_content_wrap">
				<?php
				// Single posts banner on the background
				if ( is_singular( 'post' ) || is_singular( 'attachment' ) ) {
					helion_show_post_banner( 'background' );
				}

				// Single post thumbnail and title
				get_template_part( apply_filters( 'helion_filter_get_template_part', 'templates/single-styles/' . helion_get_theme_option( 'single_style' ) ) );

				// Widgets area above page content
				$helion_body_style   = helion_get_theme_option( 'body_style' );
				$helion_widgets_name = helion_get_theme_option( 'widgets_above_page' );
				$helion_show_widgets = ! helion_is_off( $helion_widgets_name ) && is_active_sidebar( $helion_widgets_name );
				if ( $helion_show_widgets ) {
					if ( 'fullscreen' != $helion_body_style ) {
						?>
						<div class="content_wrap">
							<?php
					}
					helion_create_widgets_area( 'widgets_above_page' );
					if ( 'fullscreen' != $helion_body_style ) {
						?>
						</div><!-- </.content_wrap> -->
						<?php
					}
				}

				// Content area
				?>
				<div class="content_wrap<?php echo 'fullscreen' == $helion_body_style ? '_fullscreen' : ''; ?>">

					<div class="content">
						<?php
						// Skip link anchor to fast access to the content from keyboard
						?>
						<a id="content_skip_link_anchor" class="helion_skip_link_anchor" href="#"></a>
						<?php
						// Widgets area inside page content
						helion_create_widgets_area( 'widgets_above_content' );
