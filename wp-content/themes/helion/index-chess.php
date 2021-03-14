<?php
/**
 * The template for homepage posts with "Chess" style
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0
 */

helion_storage_set( 'blog_archive', true );

get_header();

if ( have_posts() ) {

	helion_blog_archive_start();

	$helion_stickies   = is_home() ? get_option( 'sticky_posts' ) : false;
	$helion_sticky_out = helion_get_theme_option( 'sticky_style' ) == 'columns'
							&& is_array( $helion_stickies ) && count( $helion_stickies ) > 0 && get_query_var( 'paged' ) < 1;
	if ( $helion_sticky_out ) {
		?>
		<div class="sticky_wrap columns_wrap">
		<?php
	}
	if ( ! $helion_sticky_out ) {
		?>
		<div class="chess_wrap posts_container">
		<?php
	}
	
	while ( have_posts() ) {
		the_post();
		if ( $helion_sticky_out && ! is_sticky() ) {
			$helion_sticky_out = false;
			?>
			</div><div class="chess_wrap posts_container">
			<?php
		}
		$helion_part = $helion_sticky_out && is_sticky() ? 'sticky' : 'chess';
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
