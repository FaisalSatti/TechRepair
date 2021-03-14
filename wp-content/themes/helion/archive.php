<?php
/**
 * The template file to display taxonomies archive
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0.57
 */

// Redirect to the template page (if exists) for output current taxonomy
if ( is_category() || is_tag() || is_tax() ) {
	$helion_term = get_queried_object();
	global $wp_query;
	if ( ! empty( $helion_term->taxonomy ) && ! empty( $wp_query->posts[0]->post_type ) ) {
		$helion_taxonomy  = helion_get_post_type_taxonomy( $wp_query->posts[0]->post_type );
		if ( $helion_taxonomy == $helion_term->taxonomy ) {
			$helion_template_page_id = helion_get_template_page_id( array(
				'post_type'  => $wp_query->posts[0]->post_type,
				'parent_cat' => $helion_term->term_id
			) );
			if ( 0 < $helion_template_page_id ) {
				wp_safe_redirect( get_permalink( $helion_template_page_id ) );
				exit;
			}
		}
	}
}
// If template page is not exists - display default blog archive template
get_template_part( apply_filters( 'helion_filter_get_template_part', helion_blog_archive_get_template() ) );
