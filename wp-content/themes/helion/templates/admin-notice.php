<?php
/**
 * The template to display Admin notices
 *
 * @package WordPress
 * @subpackage HELION
 * @since HELION 1.0.1
 */

$helion_theme_obj = wp_get_theme();
?>
<div class="helion_admin_notice helion_welcome_notice update-nag">
	<?php
	// Theme image
	$helion_theme_img = helion_get_file_url( 'screenshot.jpg' );
	if ( '' != $helion_theme_img ) {
		?>
		<div class="helion_notice_image"><img src="<?php echo esc_url( $helion_theme_img ); ?>" alt="<?php esc_attr_e( 'Theme screenshot', 'helion' ); ?>"></div>
		<?php
	}

	// Title
	?>
	<h3 class="helion_notice_title">
		<?php
		echo esc_html(
			sprintf(
				// Translators: Add theme name and version to the 'Welcome' message
				__( 'Welcome to %1$s v.%2$s', 'helion' ),
				$helion_theme_obj->name . ( HELION_THEME_FREE ? ' ' . __( 'Free', 'helion' ) : '' ),
				$helion_theme_obj->version
			)
		);
		?>
	</h3>
	<?php

	// Description
	?>
	<div class="helion_notice_text">
		<p class="helion_notice_text_description">
			<?php
			echo str_replace( '. ', '.<br>', wp_kses_data( $helion_theme_obj->description ) );
			?>
		</p>
		<p class="helion_notice_text_info">
			<?php
			echo wp_kses_data( __( 'Attention! Plugin "ThemeREX Addons" is required! Please, install and activate it!', 'helion' ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="helion_notice_buttons">
		<?php
		// Link to the page 'About Theme'
		?>
		<a href="<?php echo esc_url( admin_url() . 'themes.php?page=helion_about' ); ?>" class="button button-primary"><i class="dashicons dashicons-nametag"></i> 
			<?php
			echo esc_html__( 'Install plugin "ThemeREX Addons"', 'helion' );
			?>
		</a>
		<?php		
		// Dismiss this notice
		?>
		<a href="#" data-notice="admin" class="helion_hide_notice"><i class="dashicons dashicons-dismiss"></i> <span class="helion_hide_notice_text"><?php esc_html_e( 'Dismiss', 'helion' ); ?></span></a>
	</div>
</div>
