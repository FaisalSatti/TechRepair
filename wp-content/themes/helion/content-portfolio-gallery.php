<?php
/**
 * The Gallery template to display posts
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
$helion_post_format = get_post_format();
$helion_post_format = empty( $helion_post_format ) ? 'standard' : str_replace( 'post-format-', '', $helion_post_format );
$helion_image       = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );

?><div class="
<?php
if ( ! empty( $helion_template_args['slider'] ) ) {
	echo ' slider-slide swiper-slide';
} else {
	echo 'masonry_item masonry_item-1_' . esc_attr( $helion_columns );
}
?>
"><article id="post-<?php the_ID(); ?>" 
	<?php
	post_class(
		'post_item post_format_' . esc_attr( $helion_post_format )
		. ' post_layout_portfolio'
		. ' post_layout_portfolio_' . esc_attr( $helion_columns )
		. ' post_layout_gallery'
		. ' post_layout_gallery_' . esc_attr( $helion_columns )
	);
	helion_add_blog_animation( $helion_template_args );
	?>
	data-size="
		<?php
		if ( ! empty( $helion_image[1] ) && ! empty( $helion_image[2] ) ) {
			echo intval( $helion_image[1] ) . 'x' . intval( $helion_image[2] );}
		?>
	"
	data-src="
		<?php
		if ( ! empty( $helion_image[0] ) ) {
			echo esc_url( $helion_image[0] );}
		?>
	"
>
<?php

	// Sticky label
if ( is_sticky() && ! is_paged() ) {
	?>
		<span class="post_label label_sticky"></span>
		<?php
}

	// Featured image
	$helion_image_hover = 'icon';
if ( in_array( $helion_image_hover, array( 'icons', 'zoom' ) ) ) {
	$helion_image_hover = 'dots';
}
$helion_components = helion_array_get_keys_by_value( helion_get_theme_option( 'meta_parts' ) );
helion_show_post_featured(
	array(
		'hover'         => !empty( $helion_template_args['hover_style'] ) ? $helion_template_args['hover_style'] : $helion_image_hover,
		'no_links'      => ! empty( $helion_template_args['no_links'] ),
		'thumb_size'    => helion_get_thumb_size( strpos( helion_get_theme_option( 'body_style' ), 'full' ) !== false || $helion_columns < 3 ? 'masonry-big' : 'masonry-big' ),
		'thumb_only'    => true,
		'show_no_image' => true,
		'post_info'     => '<div class="post_details">'
						. '<h2 class="post_title">'
							. ( empty( $helion_template_args['no_links'] )
								? '<a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a>'
								: esc_html( get_the_title() )
								)
						. '</h2>'
						. '<div class="post_description">'
							. ( ! empty( $helion_components )
								? helion_show_post_meta(
									apply_filters(
										'helion_filter_post_meta_args', array(
											'components' => $helion_components,
											'seo'      => false,
											'echo'     => false,
										), $helion_blog_style[0], $helion_columns
									)
								)
								: ''
								)
							. ( empty( $helion_template_args['hide_excerpt'] )
								? '<div class="post_description_content">' . get_the_excerpt() . '</div>'
								: ''
								)
							. ( empty( $helion_template_args['no_links'] )
								? '<a href="' . esc_url( get_permalink() ) . '" class="theme_button post_readmore"><span class="post_readmore_label">' . esc_html__( 'Learn more', 'helion' ) . '</span></a>'
								: ''
								)
						. '</div>'
					. '</div>',
	)
);
?>
</article></div><?php
// Need opening PHP-tag above, because <article> is a inline-block element (used as column)!
