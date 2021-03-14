<?php
/**
 * The default template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0
 */

$helion_template_args = get_query_var( 'helion_template_args' );
$helion_columns = 1;
if ( is_array( $helion_template_args ) ) {
	$helion_columns    = empty( $helion_template_args['columns'] ) ? 1 : max( 1, $helion_template_args['columns'] );
	$helion_blog_style = array( $helion_template_args['type'], $helion_columns );
	if ( ! empty( $helion_template_args['slider'] ) ) {
		?><div class="slider-slide swiper-slide">
		<?php
	} elseif ( $helion_columns > 1 ) {
		?>
		<div class="column-1_<?php echo esc_attr( $helion_columns ); ?>">
		<?php
	}
}
$helion_expanded    = ! helion_sidebar_present() && helion_is_on( helion_get_theme_option( 'expand_content' ) );
$helion_post_format = get_post_format();
$helion_post_format = empty( $helion_post_format ) ? 'standard' : str_replace( 'post-format-', '', $helion_post_format );
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class( 'post_item post_layout_excerpt post_format_' . esc_attr( $helion_post_format ) );
	helion_add_blog_animation( $helion_template_args );
	?>
>
	<?php

	// Sticky label
	if ( is_sticky() && ! is_paged() ) {
		?>
		<span class="post_label label_sticky"></span>
		<?php
	}

	// Featured image
	$helion_hover = ! empty( $helion_template_args['hover'] ) && ! helion_is_inherit( $helion_template_args['hover'] )
						? $helion_template_args['hover']
						: helion_get_theme_option( 'image_hover' );


	helion_show_post_featured(
		array(
			'no_links'   => ! empty( $helion_template_args['no_links'] ),
			'hover'      => $helion_hover,
			'thumb_size' => helion_get_thumb_size( strpos( helion_get_theme_option( 'body_style' ), 'full' ) !== false ? 'full' : ( $helion_expanded ? 'huge' : 'big' ) ),
		)
	);

	// Title and post meta
	$helion_show_title = get_the_title() != '';
	$helion_components = ! empty( $helion_template_args['meta_parts'] )
							? ( is_array( $helion_template_args['meta_parts'] )
								? join( ',', $helion_template_args['meta_parts'] )
								: $helion_template_args['meta_parts']
								)
							: helion_array_get_keys_by_value( helion_get_theme_option( 'meta_parts' ) );
	$helion_show_meta  = ! empty( $helion_components ) && ! in_array( $helion_hover, array( 'border', 'pull', 'slide', 'fade' ) );
	if ( $helion_show_title || $helion_show_meta ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post meta
			if ( $helion_show_meta ) {
				do_action( 'helion_action_before_post_meta' );
				helion_show_post_meta(
					apply_filters(
						'helion_filter_post_meta_args', array(
							'components' => $helion_components,
							'seo'        => false,
						), 'excerpt', 1
					)
				);
			}

			// Post title
			if ( $helion_show_title ) {
				do_action( 'helion_action_before_post_title' );
				if ( empty( $helion_template_args['no_links'] ) ) {
					the_title( sprintf( '<h2 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
				} else {
					the_title( '<h2 class="post_title entry-title">', '</h2>' );
				}
			}

			?>
		</div><!-- .post_header -->
		<?php
	}

	// Post content
	if ( empty( $helion_template_args['hide_excerpt'] ) && helion_get_theme_option( 'excerpt_length' ) > 0 ) {
		?>
		<div class="post_content entry-content">
			<?php
			if ( helion_get_theme_option( 'blog_content' ) == 'fullpost' ) {
				// Post content area
				?>
				<div class="post_content_inner">
					<?php
					do_action( 'helion_action_before_full_post_content' );
					the_content( '' );
					do_action( 'helion_action_after_full_post_content' );
					?>
				</div>
				<?php
				// Inner pages
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
			} else {
				// Post content area
				helion_show_post_content( $helion_template_args, '<div class="post_content_inner">', '</div>' );
				// More button
				if ( empty( $helion_template_args['no_links'] ) && ! in_array( $helion_post_format, array( 'link', 'aside', 'status', 'quote' ) ) ) {
							helion_show_layout(
                            '<a class="more-link" href="' . esc_url( get_permalink() ) . '">'
                            . '<span class="more-text-icon"></span>'
                            . '<span class="more-text">' . esc_html__( 'Read More', 'helion' ) . '</span>'
                            . '</a>'
                            );
				}
			}
			?>
		</div><!-- .entry-content -->
		<?php
	}
	?>
</article>
<?php

if ( is_array( $helion_template_args ) ) {
	if ( ! empty( $helion_template_args['slider'] ) || $helion_columns > 1 ) {
		?>
		</div>
		<?php
	}
}
