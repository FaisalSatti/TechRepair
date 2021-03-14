<?php
/**
 * The template to display the background video in the header
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0.14
 */
$helion_header_video = helion_get_header_video();
$helion_embed_video  = '';
if ( ! empty( $helion_header_video ) && ! helion_is_from_uploads( $helion_header_video ) ) {
	if ( helion_is_youtube_url( $helion_header_video ) && preg_match( '/[=\/]([^=\/]*)$/', $helion_header_video, $matches ) && ! empty( $matches[1] ) ) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr( $matches[1] ); ?>"></div>
		<?php
	} else {
		?>
		<div id="background_video"><?php helion_show_layout( helion_get_embed_video( $helion_header_video ) ); ?></div>
		<?php
	}
}
