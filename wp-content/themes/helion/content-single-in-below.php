<?php
/**
 * The "in-below" template to display the content of the single post or attachment:
 * featured image and title placed inside content, title below the image
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0.62
 */
?>
<article id="post-<?php the_ID(); ?>"
	<?php
	post_class( 'post_item_single'
		. ' post_type_' . esc_attr( get_post_type() ) 
		. ' post_format_' . esc_attr( str_replace( 'post-format-', '', get_post_format() ) )
	);
	helion_add_seo_itemprops();
	?>
>
<?php

	do_action( 'helion_action_before_post_data' );

	helion_add_seo_snippets();

	// Single post thumbnail and title
	if ( is_singular( 'post' ) || is_singular( 'attachment' ) ) {
		ob_start();
		?>
		<div class="post_header_wrap<?php
			if ( has_post_thumbnail() || str_replace( 'post-format-', '', get_post_format() ) == 'image' ) {
				echo ' with_featured_image';
			}
		?>">
			<?php
			// Featured image
			helion_show_post_featured_image();
			// Post title and meta
			helion_show_post_title_and_meta();
			?>
		</div>
		<?php
		$helion_post_header = ob_get_contents();
		ob_end_clean();
		if ( strpos( $helion_post_header, 'post_featured' ) !== false
			|| strpos( $helion_post_header, 'post_title' ) !== false
			|| strpos( $helion_post_header, 'post_meta' ) !== false
		) {
			helion_show_layout( $helion_post_header );
		}
	}

	do_action( 'helion_action_before_post_content' );

	// Post content
	?>
	<div class="post_content post_content_single entry-content" itemprop="mainEntityOfPage">
		<?php
		the_content();

		do_action( 'helion_action_before_post_pagination' );

		wp_link_pages(
			array(
				'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'helion' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'helion' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			)
		);

		// Taxonomies and share
		if ( is_single() && ! is_attachment() ) {

			ob_start();

			// Post taxonomies
			the_tags( '<span class="post_meta_item post_tags">', '', '</span>' );

			// Share
			if ( helion_is_on( helion_get_theme_option( 'show_share_links' ) ) ) {
				helion_show_share_links(
					array(
						'type'    => 'block',
						'caption' => '',
						'before'  => '<span class="post_meta_item post_share">',
						'after'   => '</span>',
					)
				);
			}

			$helion_tags_output = ob_get_contents();

			ob_end_clean();

			if ( ! empty( $helion_tags_output ) ) {

				do_action( 'helion_action_before_post_meta' );

				helion_show_layout( $helion_tags_output, '<div class="post_meta post_meta_single">', '</div>' );

				do_action( 'helion_action_after_post_meta' );

			}
		}
		?>
	</div><!-- .entry-content -->


	<?php
	do_action( 'helion_action_after_post_content' );

	do_action( 'helion_action_after_post_data' );
	?>
</article>
