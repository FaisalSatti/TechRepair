<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0
 */

// Page (category, tag, archive, author) title

if ( helion_need_page_title() ) {
	helion_sc_layouts_showed( 'title', true );
	helion_sc_layouts_showed( 'postmeta', true );
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php
						// Post meta on the single post
						if ( is_single() ) {
							?>
							<div class="sc_layouts_title_meta">
							<?php
								helion_show_post_meta(
									apply_filters(
										'helion_filter_post_meta_args', array(
											'components' => helion_array_get_keys_by_value( helion_get_theme_option( 'meta_parts' ) ),
											'counters'   => helion_array_get_keys_by_value( helion_get_theme_option( 'counters' ) ),
											'seo'        => helion_is_on( helion_get_theme_option( 'seo_snippets' ) ),
										), 'header', 1
									)
								);
							?>
							</div>
							<?php
						}

						// Blog/Post title
						?>
						<div class="sc_layouts_title_title">
							<?php
							$helion_blog_title           = helion_get_blog_title();
							$helion_blog_title_text      = '';
							$helion_blog_title_class     = '';
							$helion_blog_title_link      = '';
							$helion_blog_title_link_text = '';
							if ( is_array( $helion_blog_title ) ) {
								$helion_blog_title_text      = $helion_blog_title['text'];
								$helion_blog_title_class     = ! empty( $helion_blog_title['class'] ) ? ' ' . $helion_blog_title['class'] : '';
								$helion_blog_title_link      = ! empty( $helion_blog_title['link'] ) ? $helion_blog_title['link'] : '';
								$helion_blog_title_link_text = ! empty( $helion_blog_title['link_text'] ) ? $helion_blog_title['link_text'] : '';
							} else {
								$helion_blog_title_text = $helion_blog_title;
							}
							?>
							<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr( $helion_blog_title_class ); ?>">
								<?php
								$helion_top_icon = helion_get_term_image_small();
								if ( ! empty( $helion_top_icon ) ) {
									$helion_attr = helion_getimagesize( $helion_top_icon );
									?>
									<img src="<?php echo esc_url( $helion_top_icon ); ?>" alt="<?php esc_attr_e( 'Site icon', 'helion' ); ?>"
										<?php
										if ( ! empty( $helion_attr[3] ) ) {
											helion_show_layout( $helion_attr[3] );
										}
										?>
									>
									<?php
								}
								echo wp_kses_data( $helion_blog_title_text );
								?>
							</h1>
							<?php
							if ( ! empty( $helion_blog_title_link ) && ! empty( $helion_blog_title_link_text ) ) {
								?>
								<a href="<?php echo esc_url( $helion_blog_title_link ); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html( $helion_blog_title_link_text ); ?></a>
								<?php
							}

							// Category/Tag description
							if ( is_category() || is_tag() || is_tax() ) {
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
							}

							?>
						</div>
						<?php

						// Breadcrumbs
						ob_start();
						do_action( 'helion_action_breadcrumbs' );
						$helion_breadcrumbs = ob_get_contents();
						ob_end_clean();
						helion_show_layout( $helion_breadcrumbs, '<div class="sc_layouts_title_breadcrumbs">', '</div>' );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
