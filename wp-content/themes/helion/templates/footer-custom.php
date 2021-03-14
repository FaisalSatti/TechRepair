<?php
/**
 * The template to display default site footer
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0.10
 */

$helion_footer_id = helion_get_custom_footer_id();
$helion_footer_meta = get_post_meta( $helion_footer_id, 'trx_addons_options', true );
if ( ! empty( $helion_footer_meta['margin'] ) ) {
	helion_add_inline_css( sprintf( '.page_content_wrap{padding-bottom:%s}', esc_attr( helion_prepare_css_value( $helion_footer_meta['margin'] ) ) ) );
}
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr( $helion_footer_id ); ?> footer_custom_<?php echo esc_attr( sanitize_title( get_the_title( $helion_footer_id ) ) ); ?>
						<?php
						$helion_footer_scheme = helion_get_theme_option( 'footer_scheme' );
						if ( ! empty( $helion_footer_scheme ) && ! helion_is_inherit( $helion_footer_scheme  ) ) {
							echo ' scheme_' . esc_attr( $helion_footer_scheme );
						}
						?>
						">
	<?php
	// Custom footer's layout
	do_action( 'helion_action_show_layout', $helion_footer_id );
	?>
</footer><!-- /.footer_wrap -->
