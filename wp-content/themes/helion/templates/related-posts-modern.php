<?php
/**
 * The template 'Style 1' to displaying related posts
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0
 */

$helion_link        = get_permalink();
$helion_post_format = get_post_format();
$helion_post_format = empty( $helion_post_format ) ? 'standard' : str_replace( 'post-format-', '', $helion_post_format );
?><div id="post-<?php the_ID(); ?>" <?php post_class( 'related_item post_format_' . esc_attr( $helion_post_format ) ); ?> data-post-id="<?php the_ID(); ?>">
	<?php
	helion_show_post_featured(
		array(
			'thumb_size'    => apply_filters( 'helion_filter_related_thumb_size', helion_get_thumb_size( (int) helion_get_theme_option( 'related_posts' ) == 1 ? 'huge' : 'big' ) ),
			'post_info'     => '<div class="post_header entry-header">'
									. '<div class="post_categories">' . wp_kses( helion_get_post_categories( '' ),'helion_kses_content' ) . '</div>'
									. '<h6 class="post_title entry-title"><a href="' . esc_url( $helion_link ) . '">' . wp_kses_data( get_the_title() ) . '</a></h6>'
									. ( in_array( get_post_type(), array( 'post', 'attachment' ) )
											? '<div class="post_meta"><a href="' . esc_url( $helion_link ) . '" class="post_meta_item post_date">' . wp_kses_data( helion_get_date() ) . '</a></div>'
											: '' )
								. '</div>',
		)
	);
	?>
</div>
