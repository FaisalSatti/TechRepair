<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0
 */

if ( helion_sidebar_present() ) {
	
	$helion_sidebar_type = helion_get_theme_option( 'sidebar_type' );
	if ( 'custom' == $helion_sidebar_type && ! helion_is_layouts_available() ) {
		$helion_sidebar_type = 'default';
	}
	
	// Catch output to the buffer
	ob_start();
	if ( 'default' == $helion_sidebar_type ) {
		// Default sidebar with widgets
		$helion_sidebar_name = helion_get_theme_option( 'sidebar_widgets' );
		helion_storage_set( 'current_sidebar', 'sidebar' );
		if ( is_active_sidebar( $helion_sidebar_name ) ) {
			dynamic_sidebar( $helion_sidebar_name );
		}
	} else {
		// Custom sidebar from Layouts Builder
		$helion_sidebar_id = helion_get_custom_sidebar_id();
		do_action( 'helion_action_show_layout', $helion_sidebar_id );
	}
	$helion_out = trim( ob_get_contents() );
	ob_end_clean();
	
	// If any html is present - display it
	if ( ! empty( $helion_out ) ) {
		$helion_sidebar_position    = helion_get_theme_option( 'sidebar_position' );
		$helion_sidebar_position_ss = helion_get_theme_option( 'sidebar_position_ss' );
		?>
		<div class="sidebar widget_area
			<?php
			echo ' ' . esc_attr( $helion_sidebar_position );
			echo ' sidebar_' . esc_attr( $helion_sidebar_position_ss );
			echo ' sidebar_' . esc_attr( $helion_sidebar_type );

			if ( 'float' == $helion_sidebar_position_ss ) {
				echo ' sidebar_float';
			}
			$helion_sidebar_scheme = helion_get_theme_option( 'sidebar_scheme' );
			if ( ! empty( $helion_sidebar_scheme ) && ! helion_is_inherit( $helion_sidebar_scheme ) ) {
				echo ' scheme_' . esc_attr( $helion_sidebar_scheme );
			}
			?>
		" role="complementary">
			<?php
			// Skip link anchor to fast access to the sidebar from keyboard
			?>
			<a id="sidebar_skip_link_anchor" class="helion_skip_link_anchor" href="#"></a>
			<?php
			// Single posts banner before sidebar
			helion_show_post_banner( 'sidebar' );
			// Button to show/hide sidebar on mobile
			if ( in_array( $helion_sidebar_position_ss, array( 'above', 'float' ) ) ) {
				$helion_title = apply_filters( 'helion_filter_sidebar_control_title', 'float' == $helion_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'helion' ) : '' );
				$helion_text  = apply_filters( 'helion_filter_sidebar_control_text', 'above' == $helion_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'helion' ) : '' );
				?>
				<a href="#" class="sidebar_control" title="<?php echo esc_attr( $helion_title ); ?>"><?php echo esc_html( $helion_text ); ?></a>
				<?php
			}
			?>
			<div class="sidebar_inner">
				<?php
				do_action( 'helion_action_before_sidebar' );
				helion_show_layout( preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $helion_out ) );
				do_action( 'helion_action_after_sidebar' );
				?>
			</div><!-- /.sidebar_inner -->
		</div><!-- /.sidebar -->
		<div class="clearfix"></div>
		<?php
	}
}
