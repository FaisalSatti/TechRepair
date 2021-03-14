<div class="front_page_section front_page_section_team<?php
	$helion_scheme = helion_get_theme_option( 'front_page_team_scheme' );
	if ( ! empty( $helion_scheme ) && ! helion_is_inherit( $helion_scheme ) ) {
		echo ' scheme_' . esc_attr( $helion_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( helion_get_theme_option( 'front_page_team_paddings' ) );
	if ( helion_get_theme_option( 'front_page_team_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$helion_css      = '';
		$helion_bg_image = helion_get_theme_option( 'front_page_team_bg_image' );
		if ( ! empty( $helion_bg_image ) ) {
			$helion_css .= 'background-image: url(' . esc_url( helion_get_attachment_url( $helion_bg_image ) ) . ');';
		}
		if ( ! empty( $helion_css ) ) {
			echo ' style="' . esc_attr( $helion_css ) . '"';
		}
		?>
>
<?php
	// Add anchor
	$helion_anchor_icon = helion_get_theme_option( 'front_page_team_anchor_icon' );
	$helion_anchor_text = helion_get_theme_option( 'front_page_team_anchor_text' );
if ( ( ! empty( $helion_anchor_icon ) || ! empty( $helion_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_team"'
									. ( ! empty( $helion_anchor_icon ) ? ' icon="' . esc_attr( $helion_anchor_icon ) . '"' : '' )
									. ( ! empty( $helion_anchor_text ) ? ' title="' . esc_attr( $helion_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_team_inner
	<?php
	if ( helion_get_theme_option( 'front_page_team_fullheight' ) ) {
		echo ' helion-full-height sc_layouts_flex sc_layouts_columns_middle';
	}
	?>
			"
			<?php
			$helion_css      = '';
			$helion_bg_mask  = helion_get_theme_option( 'front_page_team_bg_mask' );
			$helion_bg_color_type = helion_get_theme_option( 'front_page_team_bg_color_type' );
			if ( 'custom' == $helion_bg_color_type ) {
				$helion_bg_color = helion_get_theme_option( 'front_page_team_bg_color' );
			} elseif ( 'scheme_bg_color' == $helion_bg_color_type ) {
				$helion_bg_color = helion_get_scheme_color( 'bg_color', $helion_scheme );
			} else {
				$helion_bg_color = '';
			}
			if ( ! empty( $helion_bg_color ) && $helion_bg_mask > 0 ) {
				$helion_css .= 'background-color: ' . esc_attr(
					1 == $helion_bg_mask ? $helion_bg_color : helion_hex2rgba( $helion_bg_color, $helion_bg_mask )
				) . ';';
			}
			if ( ! empty( $helion_css ) ) {
				echo ' style="' . esc_attr( $helion_css ) . '"';
			}
			?>
	>
		<div class="front_page_section_content_wrap front_page_section_team_content_wrap content_wrap">
			<?php
			// Caption
			$helion_caption = helion_get_theme_option( 'front_page_team_caption' );
			if ( ! empty( $helion_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<h2 class="front_page_section_caption front_page_section_team_caption front_page_block_<?php echo ! empty( $helion_caption ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( $helion_caption,'helion_kses_content' ); ?></h2>
				<?php
			}

			// Description (text)
			$helion_description = helion_get_theme_option( 'front_page_team_description' );
			if ( ! empty( $helion_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_description front_page_section_team_description front_page_block_<?php echo ! empty( $helion_description ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( wpautop( $helion_description ),'helion_kses_content' ); ?></div>
				<?php
			}

			// Content (widgets)
			?>
			<div class="front_page_section_output front_page_section_team_output">
			<?php
			if ( is_active_sidebar( 'front_page_team_widgets' ) ) {
				dynamic_sidebar( 'front_page_team_widgets' );
			} elseif ( current_user_can( 'edit_theme_options' ) ) {
				if ( ! helion_exists_trx_addons() ) {
					helion_customizer_need_trx_addons_message();
				} else {
					helion_customizer_need_widgets_message( 'front_page_team_caption', 'ThemeREX Addons - Team' );
				}
			}
			?>
			</div>
		</div>
	</div>
</div>
