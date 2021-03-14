<?php
/**
 * 'Plain' template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0.62
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
	post_class( 'post_item post_layout_plain post_format_' . esc_attr( $helion_post_format ) );
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
			'thumb_bg'   => true,
			'thumb_size' => helion_get_thumb_size( 
								in_array( $helion_post_format, array( 'gallery', 'audio', 'video' ) )
									? ( strpos( helion_get_theme_option( 'body_style' ), 'full' ) !== false
										? 'full'
										: ( $helion_expanded 
											? 'huge' 
											: 'big'
											)
										)
									: 'masonry-big'
								)
		)
	);

	?><div class="post_content_wrap"><?php

		// Title and post meta
		if ( ! in_array( $helion_post_format, array( 'aside', 'status', 'link', 'quote' ) ) ) {
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
					// Post title
					if ( $helion_show_title ) {
						do_action( 'helion_action_before_post_title' );
						if ( empty( $helion_template_args['no_links'] ) ) {
							the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
						} else {
							the_title( '<h3 class="post_title entry-title">', '</h3>' );
						}
					}
					
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
					?>
				</div><!-- .post_header -->
				<?php
			}
		}

		// Post content
		if ( ! isset( $helion_template_args['excerpt_length'] ) && ! in_array( $helion_post_format, array( 'gallery', 'audio', 'video' ) ) ) {
			$helion_template_args['excerpt_length'] = 30;
		}
		if ( empty( $helion_template_args['hide_excerpt'] ) && helion_get_theme_option( 'excerpt_length' ) > 0 ) {
			?>
			<div class="post_content entry-content">
				<?php
				// Post content area
				helion_show_post_content( $helion_template_args, '<div class="post_content_inner">', '</div>' );
				// More button
				if ( empty( $helion_template_args['no_links'] ) && ! in_array( $helion_post_format, array( 'link', 'aside', 'status', 'quote' ) ) ) {
					helion_show_post_more_link( $helion_template_args, '<p>', '</p>' );
				}
				?>
			</div><!-- .entry-content -->
			<?php
		}
		?>
	</div>
</article>
<?php

if ( is_array( $helion_template_args ) ) {
	if ( ! empty( $helion_template_args['slider'] ) || $helion_columns > 1 ) {
		?>
		</div>
		<?php
	}
}
