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
	$helion_columns    = empty( $helion_template_args['columns'] ) ? 1 : max( 1, min( 3, $helion_template_args['columns'] ) );
	$helion_blog_style = array( $helion_template_args['type'], $helion_columns );
} else {
	$helion_blog_style = explode( '_', helion_get_theme_option( 'blog_style' ) );
	$helion_columns    = empty( $helion_blog_style[1] ) ? 1 : max( 1, min( 3, $helion_blog_style[1] ) );
}
$helion_expanded    = ! helion_sidebar_present() && helion_is_on( helion_get_theme_option( 'expand_content' ) );
$helion_post_format = get_post_format();
$helion_post_format = empty( $helion_post_format ) ? 'standard' : str_replace( 'post-format-', '', $helion_post_format );

?><article id="post-<?php the_ID(); ?>"	data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
		'post_item'
		. ' post_layout_chess'
		. ' post_layout_chess_' . esc_attr( $helion_columns )
		. ' post_format_' . esc_attr( $helion_post_format )
		. ( ! empty( $helion_template_args['slider'] ) ? ' slider-slide swiper-slide' : '' )
	);
	helion_add_blog_animation( $helion_template_args );
	?>
>

	<?php
	// Add anchor
	if ( 1 == $helion_columns && ! is_array( $helion_template_args ) && shortcode_exists( 'trx_sc_anchor' ) ) {
		echo do_shortcode( '[trx_sc_anchor id="post_' . esc_attr( get_the_ID() ) . '" title="' . the_title_attribute( array( 'echo' => false ) ) . '" icon="' . esc_attr( helion_get_post_icon() ) . '"]' );
	}

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
			'class'         => 1 == $helion_columns && ! is_array( $helion_template_args ) ? 'helion-full-height' : '',
			'hover'         => $helion_hover,
			'no_links'      => ! empty( $helion_template_args['no_links'] ),
			'show_no_image' => true,
			'thumb_ratio'   => '1:1',
			'thumb_bg'      => true,
			'thumb_size'    => helion_get_thumb_size(
				strpos( helion_get_theme_option( 'body_style' ), 'full' ) !== false
										? ( 1 < $helion_columns ? 'huge' : 'original' )
										: ( 2 < $helion_columns ? 'big' : 'huge' )
			),
		)
	);

	?>
	<div class="post_inner"><div class="post_inner_content"><div class="post_header entry-header">
		<?php
            do_action( 'helion_action_before_post_meta' );

            // Post meta
            $helion_components = ! empty( $helion_template_args['meta_parts'] )
                ? ( is_array( $helion_template_args['meta_parts'] )
                    ? join( ',', $helion_template_args['meta_parts'] )
                    : $helion_template_args['meta_parts']
                )
                : helion_array_get_keys_by_value( helion_get_theme_option( 'meta_parts' ) );
            $helion_post_meta  = empty( $helion_components ) || in_array( $helion_hover, array( 'border', 'pull', 'slide', 'fade' ) )
                ? ''
                : helion_show_post_meta(
                    apply_filters(
                        'helion_filter_post_meta_args', array(
                        'components' => $helion_components,
                        'seo'  => false,
                        'echo' => false,
                    ), $helion_blog_style[0], $helion_columns
                    )
                );

            helion_show_layout( $helion_post_meta );

 			do_action( 'helion_action_before_post_title' );

			// Post title
			if ( empty( $helion_template_args['no_links'] ) ) {
				the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
			} else {
				the_title( '<h3 class="post_title entry-title">', '</h3>' );
			}
			?>
		</div><!-- .entry-header -->

		<div class="post_content entry-content">
			<?php
			// Post content area
			if ( empty( $helion_template_args['hide_excerpt'] ) && helion_get_theme_option( 'excerpt_length' ) > 0 ) {
				helion_show_post_content( $helion_template_args, '<div class="post_content_inner">', '</div>' );
			}
			// Post meta
			if ( in_array( $helion_post_format, array( 'link', 'aside', 'status', 'quote' ) ) ) {
				helion_show_layout( $helion_post_meta );
			}
			// More button
			if ( empty( $helion_template_args['no_links'] ) && ! in_array( $helion_post_format, array( 'link', 'aside', 'status', 'quote' ) ) ) {
				helion_show_post_more_link( $helion_template_args, '<p>', '</p>' );
			}
			?>
		</div><!-- .entry-content -->

	</div></div><!-- .post_inner -->

</article><?php
// Need opening PHP-tag above, because <article> is a inline-block element (used as column)!
