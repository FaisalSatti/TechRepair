<?php
/**
 * The template to display the socials in the footer
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0.10
 */


// Socials
if ( helion_is_on( helion_get_theme_option( 'socials_in_footer' ) ) ) {
	$helion_output = helion_get_socials_links();
	if ( '' != $helion_output ) {
		?>
		<div class="footer_socials_wrap socials_wrap">
			<div class="footer_socials_inner">
				<?php helion_show_layout( $helion_output ); ?>
			</div>
		</div>
		<?php
	}
}
