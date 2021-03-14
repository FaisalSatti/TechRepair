<?php
/**
 * The Front Page template file.
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0.31
 */

get_header();

// If front-page is a static page
if ( get_option( 'show_on_front' ) == 'page' ) {

	// If Front Page Builder is enabled - display sections
	if ( helion_is_on( helion_get_theme_option( 'front_page_enabled' ) ) ) {

		if ( have_posts() ) {
			the_post();
		}

		$helion_sections = helion_array_get_keys_by_value( helion_get_theme_option( 'front_page_sections' ), 1, false );
		if ( is_array( $helion_sections ) ) {
			foreach ( $helion_sections as $helion_section ) {
				get_template_part( apply_filters( 'helion_filter_get_template_part', 'front-page/section', $helion_section ), $helion_section );
			}
		}

		// Else if this page is blog archive
	} elseif ( is_page_template( 'blog.php' ) ) {
		get_template_part( apply_filters( 'helion_filter_get_template_part', 'blog' ) );

		// Else - display native page content
	} else {
		get_template_part( apply_filters( 'helion_filter_get_template_part', 'page' ) );
	}

	// Else get index template to show posts
} else {
	get_template_part( apply_filters( 'helion_filter_get_template_part', 'index' ) );
}

get_footer();
