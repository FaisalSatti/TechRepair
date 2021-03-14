<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0.10
 */

// Footer sidebar
$helion_footer_name    = helion_get_theme_option( 'footer_widgets' );
$helion_footer_present = ! helion_is_off( $helion_footer_name ) && is_active_sidebar( $helion_footer_name );
if ( $helion_footer_present ) {
	helion_storage_set( 'current_sidebar', 'footer' );
	$helion_footer_wide = helion_get_theme_option( 'footer_wide' );
	ob_start();
	if ( is_active_sidebar( $helion_footer_name ) ) {
		dynamic_sidebar( $helion_footer_name );
	}
	$helion_out = trim( ob_get_contents() );
	ob_end_clean();
	if ( ! empty( $helion_out ) ) {
		$helion_out          = preg_replace( "/<\\/aside>[\r\n\s]*<aside/", '</aside><aside', $helion_out );
		$helion_need_columns = true;   //or check: strpos($helion_out, 'columns_wrap')===false;
		if ( $helion_need_columns ) {
			$helion_columns = max( 0, (int) helion_get_theme_option( 'footer_columns' ) );			
			if ( 0 == $helion_columns ) {
				$helion_columns = min( 4, max( 1, helion_tags_count( $helion_out, 'aside' ) ) );
			}
			if ( $helion_columns > 1 ) {
				$helion_out = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $helion_columns ) . ' widget', $helion_out );
			} else {
				$helion_need_columns = false;
			}
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo ! empty( $helion_footer_wide ) ? ' footer_fullwidth' : ''; ?> sc_layouts_row sc_layouts_row_type_normal">
			<div class="footer_widgets_inner widget_area_inner">
				<?php
				if ( ! $helion_footer_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $helion_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'helion_action_before_sidebar' );
				helion_show_layout( $helion_out );
				do_action( 'helion_action_after_sidebar' );
				if ( $helion_need_columns ) {
					?>
					</div><!-- /.columns_wrap -->
					<?php
				}
				if ( ! $helion_footer_wide ) {
					?>
					</div><!-- /.content_wrap -->
					<?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
