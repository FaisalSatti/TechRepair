<?php
/**
 * The template to display the single post header
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0.62
 */

if ( is_singular( 'post' ) || is_singular( 'attachment' ) ) {
	ob_start();
	?>
	<div class="post_header_wrap<?php
		if ( has_post_thumbnail() || str_replace( 'post-format-', '', get_post_format() ) == 'image' ) {
			echo ' with_featured_image';
		}
	?>">
		<div class="content_wrap">
			<?php
			// Featured image
			helion_show_post_featured_image();
			// Post title and meta
			helion_show_post_title_and_meta();
			?>
		</div>
	</div>
	<?php
	$helion_post_header = ob_get_contents();
	ob_end_clean();
	if ( strpos( $helion_post_header, 'post_featured' ) !== false
		|| strpos( $helion_post_header, 'post_title' ) !== false
		|| strpos( $helion_post_header, 'post_meta' ) !== false
	) {
		helion_show_layout( $helion_post_header );
	}
}
