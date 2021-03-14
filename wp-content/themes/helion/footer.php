<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0
 */

							// Widgets area inside page content
							helion_create_widgets_area( 'widgets_below_content' );
							?>
						</div><!-- </.content> -->
					<?php

					// Show main sidebar
					get_sidebar();
					?>
					</div><!-- </.content_wrap> -->
					<?php

					// Widgets area below page content and related posts below page content
					$helion_body_style = helion_get_theme_option( 'body_style' );
					$helion_widgets_name = helion_get_theme_option( 'widgets_below_page' );
					$helion_show_widgets = ! helion_is_off( $helion_widgets_name ) && is_active_sidebar( $helion_widgets_name );
					$helion_show_related = is_single() && helion_get_theme_option( 'related_position' ) == 'below_page';
					if ( $helion_show_widgets || $helion_show_related ) {
						if ( 'fullscreen' != $helion_body_style ) {
							?>
							<div class="content_wrap">
							<?php
						}
						// Show related posts before footer
						if ( $helion_show_related ) {
							do_action( 'helion_action_related_posts' );
						}

						// Widgets area below page content
						if ( $helion_show_widgets ) {
							helion_create_widgets_area( 'widgets_below_page' );
						}
						if ( 'fullscreen' != $helion_body_style ) {
							?>
							</div><!-- </.content_wrap> -->
							<?php
						}
					}
					?>
			</div><!-- </.page_content_wrap> -->

			<?php
			// Single posts banner before footer
			if ( is_singular( 'post' ) ) {
				helion_show_post_banner('footer');
			}
			
			// Skip link anchor to fast access to the footer from keyboard
			?>
			<a id="footer_skip_link_anchor" class="helion_skip_link_anchor" href="#"></a>
			<?php
			
			// Footer
			$helion_footer_type = helion_get_theme_option( 'footer_type' );
			if ( 'custom' == $helion_footer_type && ! helion_is_layouts_available() ) {
				$helion_footer_type = 'default';
			}
			get_template_part( apply_filters( 'helion_filter_get_template_part', "templates/footer-{$helion_footer_type}" ) );
			?>

		</div><!-- /.page_wrap -->

	</div><!-- /.body_wrap -->

	<?php wp_footer(); ?>

</body>
</html>