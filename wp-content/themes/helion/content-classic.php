<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0
 */

$helion_template_args = get_query_var( 'helion_template_args' );
if ( is_array( $helion_template_args ) ) {
	$helion_columns    = empty( $helion_template_args['columns'] ) ? 2 : max( 1, $helion_template_args['columns'] );
	$helion_blog_style = array( $helion_template_args['type'], $helion_columns );
} else {
	$helion_blog_style = explode( '_', helion_get_theme_option( 'blog_style' ) );
	$helion_columns    = empty( $helion_blog_style[1] ) ? 2 : max( 1, $helion_blog_style[1] );
}
$helion_expanded   = ! helion_sidebar_present() && helion_is_on( helion_get_theme_option( 'expand_content' ) );

$helion_components = ! empty( $helion_template_args['meta_parts'] )
						? ( is_array( $helion_template_args['meta_parts'] )
							? join( ',', $helion_template_args['meta_parts'] )
							: $helion_template_args['meta_parts']
							)
						: helion_array_get_keys_by_value( helion_get_theme_option( 'meta_parts' ) );

$helion_post_format = get_post_format();
$helion_post_format = empty( $helion_post_format ) ? 'standard' : str_replace( 'post-format-', '', $helion_post_format );

?><div class="<?php
	if ( ! empty( $helion_template_args['slider'] ) ) {
		echo ' slider-slide swiper-slide';
	} else {
		echo ( 'classic' == $helion_blog_style[0] ? 'column' : 'masonry_item masonry_item' ) . '-1_' . esc_attr( $helion_columns );
	}
?>"><article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
		'post_item post_format_' . esc_attr( $helion_post_format )
				. ' post_layout_classic post_layout_classic_' . esc_attr( $helion_columns )
				. ' post_layout_' . esc_attr( $helion_blog_style[0] )
				. ' post_layout_' . esc_attr( $helion_blog_style[0] ) . '_' . esc_attr( $helion_columns )
	);
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
			'thumb_size' => helion_get_thumb_size(
				'classic' == $helion_blog_style[0]
						? ( strpos( helion_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $helion_columns > 2 ? 'big' : 'huge' )
								: ( $helion_columns > 2
									? ( $helion_expanded ? 'med' : 'small' )
									: ( $helion_expanded ? 'big' : 'med' )
									)
							)
						: ( strpos( helion_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $helion_columns > 2 ? 'masonry-big' : 'full' )
								: ( $helion_columns <= 2 && $helion_expanded ? 'masonry-big' : 'masonry' )
							)
			),
			'hover'      => $helion_hover,
			'no_links'   => ! empty( $helion_template_args['no_links'] ),
		)
	);

	if ( ! in_array( $helion_post_format, array( 'link', 'aside', 'status', 'quote' ) ) ) {
		?>
		<div class="post_header entry-header">
			<?php
            do_action( 'helion_action_before_post_meta' );

            // Post meta
            if ( ! empty( $helion_components ) && ! in_array( $helion_hover, array( 'border', 'pull', 'slide', 'fade' ) ) ) {
                helion_show_post_meta(
                    apply_filters(
                        'helion_filter_post_meta_args', array(
                        'components' => $helion_components,
                        'seo'        => false,
                    ), $helion_blog_style[0], $helion_columns
                    )
                );
            }

            do_action( 'helion_action_after_post_meta' );

			do_action( 'helion_action_before_post_title' );

			// Post title
			if ( empty( $helion_template_args['no_links'] ) ) {
				the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
			} else {
				the_title( '<h4 class="post_title entry-title">', '</h4>' );
			}
			?>
		</div><!-- .entry-header -->
		<?php
	}

	// Post content area
	ob_start();

	// Post content
	if ( empty( $helion_template_args['hide_excerpt'] ) && helion_get_theme_option( 'excerpt_length' ) > 0 ) {
		helion_show_post_content( $helion_template_args, '<div class="post_content_inner">', '</div>' );
	}

	// Post meta
	if ( in_array( $helion_post_format, array( 'link', 'aside', 'status', 'quote' ) ) ) {
		if ( ! empty( $helion_components ) ) {
			helion_show_post_meta(
				apply_filters(
					'helion_filter_post_meta_args', array(
						'components' => $helion_components,
					), $helion_blog_style[0], $helion_columns
				)
			);
		}
	}
		
	// More button
	if ( empty( $helion_template_args['no_links'] ) && ! empty( $helion_template_args['more_text'] ) && ! in_array( $helion_post_format, array( 'link', 'aside', 'status', 'quote' ) ) ) {
		helion_show_post_more_link( $helion_template_args, '<p>', '</p>' );
	}

	$helion_content = ob_get_contents();
	ob_end_clean();

	helion_show_layout( $helion_content, '<div class="post_content entry-content">', '</div><!-- .entry-content -->' );
	?>

</article></div><?php
// Need opening PHP-tag above, because <div> is a inline-block element (used as column)!
