<?php
/**
 * The template to display the site logo in the footer
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0.10
 */

// Logo
if ( helion_is_on( helion_get_theme_option( 'logo_in_footer' ) ) ) {
	$helion_logo_image = helion_get_logo_image( 'footer' );
	$helion_logo_text  = get_bloginfo( 'name' );
	if ( ! empty( $helion_logo_image['logo'] ) || ! empty( $helion_logo_text ) ) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if ( ! empty( $helion_logo_image['logo'] ) ) {
					$helion_attr = helion_getimagesize( $helion_logo_image['logo'] );
					echo '<a href="' . esc_url( home_url( '/' ) ) . '">'
							. '<img src="' . esc_url( $helion_logo_image['logo'] ) . '"'
								. ( ! empty( $helion_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $helion_logo_image['logo_retina'] ) . ' 2x"' : '' )
								. ' class="logo_footer_image"'
								. ' alt="' . esc_attr__( 'Site logo', 'helion' ) . '"'
								. ( ! empty( $helion_attr[3] ) ? ' ' . wp_kses_data( $helion_attr[3] ) : '' )
							. '>'
						. '</a>';
				} elseif ( ! empty( $helion_logo_text ) ) {
					echo '<h1 class="logo_footer_text">'
							. '<a href="' . esc_url( home_url( '/' ) ) . '">'
								. esc_html( $helion_logo_text )
							. '</a>'
						. '</h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
