<?php
/**
 * The template to display the portfolio single page
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.5
 */

get_header();
$helion_posts_navigation = helion_get_theme_option( 'posts_navigation' );
while ( have_posts() ) { the_post();

	$meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);

	do_action('trx_addons_action_before_article', 'portfolio.single');
	
	?><article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>" <?php post_class( 'portfolio_page itemscope portfolio_page_details_'.esc_attr($meta['details_position']) ); trx_addons_seo_snippets('', 'Article'); ?>><?php

		do_action('trx_addons_action_article_start', 'portfolio.single');

		// Project details before the content
		if ( ! empty($meta['subtitle']) || has_excerpt() || ( ! empty($meta['details']) && count($meta['details']) > 0 && ! empty($meta['details'][0]['title']) ) ) {
			if (in_array($meta['details_position'], array('right', 'bottom'))) {
				ob_start();
			}
			?><section class="portfolio_page_details_wrap<?php
				if (in_array($meta['details_position'], array('right', 'left'))) echo ' sc_column_fixed';
			?>"><?php
				// Title
				if ( ! trx_addons_sc_layouts_showed('title') && $meta['move_title'] == true ) {
					?><h2 class="portfolio_page_title"><?php the_title(); ?></h2><?php
					// Meta
					if ( ! trx_addons_sc_layouts_showed('postmeta') && $meta['disable_meta'] == false ) {
						?><div class="portfolio_page_meta"><?php
							trx_addons_sc_show_post_meta('portfolio_single', apply_filters('trx_addons_filter_post_meta_args', array(
										'components' => 'views,comments,likes,share',
										'seo' => false
										), 'portfolio_single', 1)
									);
						?></div><?php
						trx_addons_sc_layouts_showed('postmeta', true);
					}
				}
				// Subtitle
				if (!empty($meta['subtitle'])) {
					?><h5 class="portfolio_page_subtitle"><?php trx_addons_show_layout(trx_addons_prepare_macros($meta['subtitle'])); ?></h5><?php
				}
				// Excerpt
				if (has_excerpt()) {
					?><div class="portfolio_page_description"><?php
						the_excerpt();
					?></div><?php
				}
				// Details
				if (!empty($meta['details']) && count($meta['details']) > 0 && !empty($meta['details'][0]['title'])) {
					?><div class="portfolio_page_details"><?php
						foreach($meta['details'] as $item) {
							if (empty($item['title']) || empty($item['value'])) continue;
							?><span class="portfolio_page_details_item"><?php
								// Title
								?><span class="portfolio_page_details_item_title"><?php echo esc_html($item['title']); ?></span><?php
								// Value
								if (!empty($item['link'])) {
									?><a href="<?php echo esc_url($item['link']); ?>"<?php
								} else {
									?><span<?php
								}
								?> class="portfolio_page_details_item_value"><?php
									// Icon
									if (!empty($item['icon'])) {
										$icon = $item['icon'];
										$img = $svg = '';
										$icon_type = 'icons';
										if (trx_addons_is_url($icon)) {
											if (strpos($icon, '.svg') !== false) {
												$svg = $icon;
												$icon_type = 'svg';
											} else {
												$img = $icon;
												$icon_type = 'images';
											}
											$icon = basename($icon);
										}
										?><span class="portfolio_page_details_item_icon sc_icon_type_<?php echo esc_attr($icon_type); ?> <?php echo esc_attr($icon); ?>"><?php
											if (!empty($svg)) {
												trx_addons_show_layout(trx_addons_get_svg_from_file($svg));
											} else if (!empty($img)) {
												$attr = trx_addons_getimagesize($img);
												?><img class="sc_icon_as_image" src="<?php echo esc_url($img); ?>" alt="<?php esc_attr_e('Icon', 'helion'); ?>"<?php echo (!empty($attr[3]) ? ' '.trim($attr[3]) : ''); ?>><?php
											}
										?></span><?php
									}
									echo esc_html($item['value']);
								if (!empty($item['link'])) {
									?></a><?php
								} else {
									?></span><?php
								}
							?></span><?php
						}
						// Share
						$trx_addons_output = trx_addons_get_share_links(array(
								'type' => 'list',
								'caption' => '',
								'echo' => false
							));
						if ($trx_addons_output) {
							?><span class="portfolio_page_details_item portfolio_page_details_share">
                                <span class="portfolio_page_details_item_value"><?php trx_addons_show_layout($trx_addons_output); ?></span><?php
							?></span><?php
						}
					?></div><?php
				}
			?></section><?php
			if (in_array($meta['details_position'], array('right', 'bottom'))) {
				$details = ob_get_contents();
				ob_end_clean();
			}
		}

		// Post content
		?><section class="portfolio_page_content_wrap"><?php
			// Gallery
			if (!empty($meta['gallery']) && $meta['gallery_position']!='none') {
				$images = explode('|', $meta['gallery']);
				if ( in_array( $meta['gallery_position'], array( 'inside', 'bottom' ) ) ) {
					ob_start();
				}
				?><div class="portfolio_page_gallery<?php echo esc_attr(($meta['gallery_wide']) && !in_array($meta['details_position'], array('left', 'right')) ? ' alignwide' : '')?>"><?php
					?><div class="portfolio_page_gallery_content portfolio_page_gallery_type_<?php echo esc_attr($meta['gallery_layout']); ?>"><?php
						// Layout: Slider
						if ($meta['gallery_layout'] == 'slider') {
							trx_addons_show_layout(trx_addons_get_slider_layout(array(
										'mode' => 'custom',
										), $images));
						
						// Layout: Grid or Stream
						} else if (strpos($meta['gallery_layout'], 'grid_')!==false || strpos($meta['gallery_layout'], 'masonry_')!==false || $meta['gallery_layout'] == 'stream') {
							$style   = explode('_', $meta['gallery_layout']);
							$type    = $style[0];
							$columns = empty($style[1]) ? 1 : max(2, $style[1]);
							if ($columns > 1 && $type == 'grid') {
								?><div class="portfolio_page_columns_wrap <?php
									echo esc_attr(trx_addons_get_columns_wrap_class())
										. ' columns_padding_bottom'
										. esc_attr( trx_addons_add_columns_in_single_row( $columns, $images ) );
								?>"><?php
							}
							foreach($images as $img) {
								$img_title = '';
								if (($img_id = attachment_url_to_postid($img)) > 0) {
									$img_title = wp_get_attachment_caption($img_id);
								}
								?><div class="<?php
									if ($columns > 1 && $type == 'grid')
										echo esc_attr(trx_addons_get_column_class(1, $columns));
									else
										echo 'portfolio_page_gallery_item';
								?>">
									<figure><?php
										$thumb = trx_addons_add_thumb_size($img, apply_filters('trx_addons_filter_thumb_size', trx_addons_get_thumb_size($type=='stream'
																																	? 'full'
																																	: ($type=='masonry'
																																		? ($columns > 2 ? 'masonry-big' : 'masonry-big')
																																		: ($columns > 2 ? 'big' : 'big'))),
																																'portfolio-single'));
										$attr = trx_addons_getimagesize($thumb);
										?><a href="<?php echo esc_url($img); ?>" title="<?php echo esc_attr($img_title); ?>"><img src="<?php echo esc_url($thumb); ?>" alt="<?php esc_attr_e('Gallery item', 'helion'); ?>"<?php if (!empty($attr[3])) echo ' '.trim($attr[3]); ?>></a><?php
										if (!empty($img_title)) {
											?><figcaption class="wp-caption-text gallery-caption"><?php echo esc_html($img_title); ?></figcaption><?php
										}
									?></figure>
								</div><?php
							}
							if ($columns > 1 && $type == 'grid') {
								?></div><?php
							}
						}
					?></div><?php
					if (!empty($meta['gallery_description'])) {
						?><div class="portfolio_page_gallery_description"><?php
							trx_addons_show_layout(trx_addons_prepare_macros($meta['gallery_description']));
						?></div><?php
					}
				?></div><?php
				// Video
				if (!empty($meta['video'])) {
					?><div class="portfolio_page_video"><?php
						?><div class="portfolio_page_video_content"><?php
							trx_addons_show_layout(trx_addons_get_video_layout(array(
																					'link' => $meta['video']
																				)));
						?></div><?php
						if (!empty($meta['video_description'])) {
							?><div class="portfolio_page_video_description"><?php
								trx_addons_show_layout(trx_addons_prepare_macros($meta['video_description']));
							?></div><?php
						}
					?></div><?php
				}
				if ( in_array( $meta['gallery_position'], array( 'inside', 'bottom' ) ) ) {
					$gallery = ob_get_contents();
					ob_end_clean();
				}
			}

			// Image
			if ( ! trx_addons_sc_layouts_showed('featured') && has_post_thumbnail() && (empty($meta['gallery']) || in_array($meta['gallery_position'], array('none', 'bottom', 'inside'))) ) {
				?><div class="portfolio_page_featured<?php echo esc_attr(($meta['featured_wide']) && !in_array($meta['details_position'], array('left', 'right')) ? ' alignwide' : '')?>"><?php
					the_post_thumbnail(
										apply_filters('trx_addons_filter_thumb_size', 'full', 'portfolio-single'),
										trx_addons_seo_image_params(array(
																		'alt' => the_title_attribute( array( 'echo' => false ) )
																		))
										);
				?></div><?php
			}
			
			// Title
			if ( ! trx_addons_sc_layouts_showed('title') && $meta['move_title'] == false ) {
				?><h2 class="portfolio_page_title"><?php the_title(); ?></h2><?php
				// Meta
				if ( ! trx_addons_sc_layouts_showed('postmeta') && $meta['disable_meta'] == false ) {
					?><div class="portfolio_page_meta"><?php
						trx_addons_sc_show_post_meta('portfolio_single', apply_filters('trx_addons_filter_post_meta_args', array(
									'components' => 'views,comments,likes,share',
									'seo' => false
									), 'portfolio_single', 1)
								);
					?></div><?php
					trx_addons_sc_layouts_showed('postmeta', true);
				}
			}
		
			// Post content
			if ( ! empty( get_the_content() ) ){
				?><div class="portfolio_page_content entry-content"<?php trx_addons_seo_snippets('articleBody'); ?>><?php
					if ( $meta['gallery_position'] == 'inside' && ! empty( $gallery ) ) {
						$place = '%%GALLERY%%';
						$content = get_the_content();

						if ( strpos( $content, $place ) !== false ) {
	                        trx_addons_show_layout( apply_filters( 'the_content', preg_replace('/(\<p\>\s*)?' . $place . '(\s*\<\/p\>)/i', $gallery, $content ) ) );
						} else {
							the_content();
						}
					} else {
						the_content();
					}
				?></div><?php
			}
			// Gallery after the content
			if ( $meta['gallery_position'] == 'bottom' && ! empty( $gallery ) ) {
				trx_addons_show_layout($gallery);
			}
		
		?></section><!-- .entry-content --><?php

		// Project details after the content
		if (in_array($meta['details_position'], array('right', 'bottom')) && !empty($details)) {
			trx_addons_show_layout($details);
		}

		do_action('trx_addons_action_article_end', 'portfolio.single');

	?></article><?php

    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) {
    	if ( helion_get_theme_option( 'show_comments_portfolio' ) == true ) {
        comments_template();
    	}
    }

	  // Previous/next post navigation.
		if ( helion_get_theme_option( 'show_navigation_portfolio' ) == true  ) {
			do_action( 'helion_action_before_post_navigation' );
			?>
			<div class="nav-links-single">
				<?php
				the_post_navigation(
					array(
						'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next', 'helion' ) . '</span> '
							. '<span class="nav-arrow-portfolio"></span>',
						'prev_text' => '<span class="nav-arrow-portfolio"></span>'
							. '<span class="screen-reader-text">' . esc_html__( 'Prev', 'helion' ) . '</span> ',
					)
				);
				?>
			</div>
			<?php
			do_action( 'helion_action_after_post_navigation' );
		}

    ob_start();
	do_action('trx_addons_action_after_article', 'portfolio.single');
    $helion_portfolio_related = ob_get_contents();
    ob_end_clean();

    helion_show_layout(str_replace('class="related_wrap portfolio_page_related ', ('class="related_wrap portfolio_page_related ' . esc_attr($meta['related_wide'] ? 'alignwide' : '') . ' '), $helion_portfolio_related ));

}

get_footer();
