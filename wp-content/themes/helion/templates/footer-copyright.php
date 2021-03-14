<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0.10
 */

// Copyright area
?> 
<div class="footer_copyright_wrap
<?php
$helion_copyright_scheme = helion_get_theme_option( 'copyright_scheme' );
if ( ! empty( $helion_copyright_scheme ) && ! helion_is_inherit( $helion_copyright_scheme  ) ) {
	echo ' scheme_' . esc_attr( $helion_copyright_scheme );
}
?>
				">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text">
			<?php
				$helion_copyright = helion_get_theme_option( 'copyright' );
			if ( ! empty( $helion_copyright ) ) {
				// Replace {{Y}} or {Y} with the current year
				$helion_copyright = str_replace( array( '{{Y}}', '{Y}' ), date( 'Y' ), $helion_copyright );
				// Replace {{...}} and ((...)) on the <i>...</i> and <b>...</b>
				$helion_copyright = helion_prepare_macros( $helion_copyright );
				// Display copyright
				echo wp_kses( nl2br( $helion_copyright ),'helion_kses_content' );
			}
			?>
			</div>
		</div>
	</div>
</div>
