<?php
/**
 * The template to display the widgets area in the header
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0
 */

// Header sidebar
$helion_header_name    = helion_get_theme_option( 'header_widgets' );
$helion_header_present = ! helion_is_off( $helion_header_name ) && is_active_sidebar( $helion_header_name );
if ( $helion_header_present ) {
	helion_storage_set( 'current_sidebar', 'header' );
	$helion_header_wide = helion_get_theme_option( 'header_wide' );
	ob_start();
	if ( is_active_sidebar( $helion_header_name ) ) {
		dynamic_sidebar( $helion_header_name );
	}
	$helion_widgets_output = ob_get_contents();
	ob_end_clean();
	if ( ! empty( $helion_widgets_output ) ) {
		$helion_widgets_output = preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $helion_widgets_output );
		$helion_need_columns   = strpos( $helion_widgets_output, 'columns_wrap' ) === false;
		if ( $helion_need_columns ) {
			$helion_columns = max( 0, (int) helion_get_theme_option( 'header_columns' ) );
			if ( 0 == $helion_columns ) {
				$helion_columns = min( 6, max( 1, helion_tags_count( $helion_widgets_output, 'aside' ) ) );
			}
			if ( $helion_columns > 1 ) {
				$helion_widgets_output = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $helion_columns ) . ' widget', $helion_widgets_output );
			} else {
				$helion_need_columns = false;
			}
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo ! empty( $helion_header_wide ) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<div class="header_widgets_inner widget_area_inner">
				<?php
				if ( ! $helion_header_wide ) {
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
				helion_show_layout( $helion_widgets_output );
				do_action( 'helion_action_after_sidebar' );
				if ( $helion_need_columns ) {
					?>
					</div>	<!-- /.columns_wrap -->
					<?php
				}
				if ( ! $helion_header_wide ) {
					?>
					</div>	<!-- /.content_wrap -->
					<?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
