<?php
/**
 * The Portfolio template to display the content
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
		. ( is_sticky() && ! is_paged() ? ' sticky' : '' )
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

        $helion_image_hover = ! empty( $helion_template_args['hover'] ) && ! helion_is_inherit( $helion_template_args['hover'] )
            ? $helion_template_args['hover']
            : helion_get_theme_option( 'image_hover' );


        if ( 'dots' == $helion_image_hover ) {
            $helion_post_link   = empty( $helion_template_args['no_links'] )
                ? ( ! empty( $helion_template_args['link'] )
                    ? $helion_template_args['link']
                    : get_permalink()
                )
                : '';
            $helion_target      = ! empty( $helion_post_link ) && false === strpos( $helion_post_link, home_url() )
                ? ' target="_blank" rel="nofollow"'
                : '';
        }
	// Featured image
	helion_show_post_featured(
		array(
			'hover'         =>  $helion_image_hover,
			'no_links'      => ! empty( $helion_template_args['no_links'] ),
			'thumb_size'    => helion_get_thumb_size(
									strpos( helion_get_theme_option( 'body_style' ), 'full' ) !== false || $helion_columns < 3
										? 'masonry-big'
										: 'masonry-big' 	// Use -big because when image is square 'masonry' is blur!
								),
			'show_no_image' => true,
			'class'         => 'dots' == $helion_image_hover ? 'hover_with_info' : '',
			'post_info'     => 'dots' == $helion_image_hover ? '<div class="post_info">' . esc_html( get_the_title() ) . '</div>' : '',
		)
	);

	?>
</article></div><?php
// Need opening PHP-tag above, because <article> is a inline-block element (used as column)!