<?php
/**
 * The template for homepage posts with custom style
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0.50
 */

helion_storage_set( 'blog_archive', true );

get_header();

if ( have_posts() ) {

	$helion_blog_style = helion_get_theme_option( 'blog_style' );
	$helion_parts      = explode( '_', $helion_blog_style );
	$helion_columns    = ! empty( $helion_parts[1] ) ? max( 1, min( 6, (int) $helion_parts[1] ) ) : 1;
	$helion_blog_id    = helion_get_custom_blog_id( $helion_blog_style );
	$helion_blog_meta  = helion_get_custom_layout_meta( $helion_blog_id );
	if ( ! empty( $helion_blog_meta['margin'] ) ) {
		helion_add_inline_css( sprintf( '.page_content_wrap{padding-top:%s}', esc_attr( helion_prepare_css_value( $helion_blog_meta['margin'] ) ) ) );
	}
	$helion_custom_style = ! empty( $helion_blog_meta['scripts_required'] ) ? $helion_blog_meta['scripts_required'] : 'none';

	helion_blog_archive_start();

	$helion_classes    = 'posts_container blog_custom_wrap' 
							. ( ! helion_is_off( $helion_custom_style )
								? sprintf( ' %s_wrap', $helion_custom_style )
								: ( $helion_columns > 1 
									? ' columns_wrap columns_padding_bottom' 
									: ''
									)
								);
	$helion_stickies   = is_home() ? get_option( 'sticky_posts' ) : false;
	$helion_sticky_out = helion_get_theme_option( 'sticky_style' ) == 'columns'
							&& is_array( $helion_stickies ) && count( $helion_stickies ) > 0 && get_query_var( 'paged' ) < 1;
	if ( $helion_sticky_out ) {
		?>
		<div class="sticky_wrap columns_wrap">
		<?php
	}
	if ( ! $helion_sticky_out ) {
		if ( helion_get_theme_option( 'first_post_large' ) && ! is_paged() && ! in_array( helion_get_theme_option( 'body_style' ), array( 'fullwide', 'fullscreen' ) ) ) {
			the_post();
			get_template_part( apply_filters( 'helion_filter_get_template_part', 'content', 'excerpt' ), 'excerpt' );
		}
		?>
		<div class="<?php echo esc_attr( $helion_classes ); ?>">
		<?php
	}
	while ( have_posts() ) {
		the_post();
		if ( $helion_sticky_out && ! is_sticky() ) {
			$helion_sticky_out = false;
			?>
			</div><div class="<?php echo esc_attr( $helion_classes ); ?>">
			<?php
		}
		$helion_part = $helion_sticky_out && is_sticky() ? 'sticky' : 'custom';
		get_template_part( apply_filters( 'helion_filter_get_template_part', 'content', $helion_part ), $helion_part );
	}
	?>
	</div>
	<?php

	helion_show_pagination();

	helion_blog_archive_end();

} else {

	if ( is_search() ) {
		get_template_part( apply_filters( 'helion_filter_get_template_part', 'content', 'none-search' ), 'none-search' );
	} else {
		get_template_part( apply_filters( 'helion_filter_get_template_part', 'content', 'none-archive' ), 'none-archive' );
	}
}

get_footer();
