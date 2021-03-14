<?php
/**
 * The custom template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0.50
 */

$helion_template_args = get_query_var( 'helion_template_args' );
if ( is_array( $helion_template_args ) ) {
	$helion_columns    = empty( $helion_template_args['columns'] ) ? 2 : max( 1, $helion_template_args['columns'] );
	$helion_blog_style = array( $helion_template_args['type'], $helion_columns );
} else {
	$helion_blog_style = explode( '_', helion_get_theme_option( 'blog_style' ) );
	$helion_columns    = empty( $helion_blog_style[1] ) ? 2 : max( 1, $helion_blog_style[1] );
}
$helion_blog_id       = helion_get_custom_blog_id( join( '_', $helion_blog_style ) );
$helion_blog_style[0] = str_replace( 'blog-custom-', '', $helion_blog_style[0] );
$helion_expanded      = ! helion_sidebar_present() && helion_is_on( helion_get_theme_option( 'expand_content' ) );
$helion_components    = ! empty( $helion_template_args['meta_parts'] )
							? ( is_array( $helion_template_args['meta_parts'] )
								? join( ',', $helion_template_args['meta_parts'] )
								: $helion_template_args['meta_parts']
								)
							: helion_array_get_keys_by_value( helion_get_theme_option( 'meta_parts' ) );
$helion_post_format   = get_post_format();
$helion_post_format   = empty( $helion_post_format ) ? 'standard' : str_replace( 'post-format-', '', $helion_post_format );

$helion_blog_meta     = helion_get_custom_layout_meta( $helion_blog_id );
$helion_custom_style  = ! empty( $helion_blog_meta['scripts_required'] ) ? $helion_blog_meta['scripts_required'] : 'none';

if ( ! empty( $helion_template_args['slider'] ) || $helion_columns > 1 || ! helion_is_off( $helion_custom_style ) ) {
	?><div class="
		<?php
		if ( ! empty( $helion_template_args['slider'] ) ) {
			echo 'slider-slide swiper-slide';
		} else {
			echo esc_attr( ( helion_is_off( $helion_custom_style ) ? 'column' : sprintf( '%1$s_item %1$s_item', $helion_custom_style ) ) . "-1_{$helion_columns}" );
		}
		?>
	">
	<?php
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
			'post_item post_format_' . esc_attr( $helion_post_format )
					. ' post_layout_custom post_layout_custom_' . esc_attr( $helion_columns )
					. ' post_layout_' . esc_attr( $helion_blog_style[0] )
					. ' post_layout_' . esc_attr( $helion_blog_style[0] ) . '_' . esc_attr( $helion_columns )
					. ( ! helion_is_off( $helion_custom_style )
						? ' post_layout_' . esc_attr( $helion_custom_style )
							. ' post_layout_' . esc_attr( $helion_custom_style ) . '_' . esc_attr( $helion_columns )
						: ''
						)
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
	// Custom layout
	do_action( 'helion_action_show_layout', $helion_blog_id, get_the_ID() );
	?>
</article><?php
if ( ! empty( $helion_template_args['slider'] ) || $helion_columns > 1 || ! helion_is_off( $helion_custom_style ) ) {
	?></div><?php
	// Need opening PHP-tag above just after </div>, because <div> is a inline-block element (used as column)!
}
