<div class="front_page_section front_page_section_woocommerce<?php
	$helion_scheme = helion_get_theme_option( 'front_page_woocommerce_scheme' );
	if ( ! empty( $helion_scheme ) && ! helion_is_inherit( $helion_scheme ) ) {
		echo ' scheme_' . esc_attr( $helion_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( helion_get_theme_option( 'front_page_woocommerce_paddings' ) );
	if ( helion_get_theme_option( 'front_page_woocommerce_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$helion_css      = '';
		$helion_bg_image = helion_get_theme_option( 'front_page_woocommerce_bg_image' );
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
	$helion_anchor_icon = helion_get_theme_option( 'front_page_woocommerce_anchor_icon' );
	$helion_anchor_text = helion_get_theme_option( 'front_page_woocommerce_anchor_text' );
if ( ( ! empty( $helion_anchor_icon ) || ! empty( $helion_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_woocommerce"'
									. ( ! empty( $helion_anchor_icon ) ? ' icon="' . esc_attr( $helion_anchor_icon ) . '"' : '' )
									. ( ! empty( $helion_anchor_text ) ? ' title="' . esc_attr( $helion_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_woocommerce_inner
	<?php
	if ( helion_get_theme_option( 'front_page_woocommerce_fullheight' ) ) {
		echo ' helion-full-height sc_layouts_flex sc_layouts_columns_middle';
	}
	?>
			"
			<?php
			$helion_css      = '';
			$helion_bg_mask  = helion_get_theme_option( 'front_page_woocommerce_bg_mask' );
			$helion_bg_color_type = helion_get_theme_option( 'front_page_woocommerce_bg_color_type' );
			if ( 'custom' == $helion_bg_color_type ) {
				$helion_bg_color = helion_get_theme_option( 'front_page_woocommerce_bg_color' );
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
		<div class="front_page_section_content_wrap front_page_section_woocommerce_content_wrap content_wrap woocommerce">
			<?php
			// Content wrap with title and description
			$helion_caption     = helion_get_theme_option( 'front_page_woocommerce_caption' );
			$helion_description = helion_get_theme_option( 'front_page_woocommerce_description' );
			if ( ! empty( $helion_caption ) || ! empty( $helion_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				// Caption
				if ( ! empty( $helion_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<h2 class="front_page_section_caption front_page_section_woocommerce_caption front_page_block_<?php echo ! empty( $helion_caption ) ? 'filled' : 'empty'; ?>">
					<?php
						echo wp_kses( $helion_caption,'helion_kses_content' );
					?>
					</h2>
					<?php
				}

				// Description (text)
				if ( ! empty( $helion_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<div class="front_page_section_description front_page_section_woocommerce_description front_page_block_<?php echo ! empty( $helion_description ) ? 'filled' : 'empty'; ?>">
					<?php
						echo wp_kses( wpautop( $helion_description ),'helion_kses_content' );
					?>
					</div>
					<?php
				}
			}

			// Content (widgets)
			?>
			<div class="front_page_section_output front_page_section_woocommerce_output list_products shop_mode_thumbs">
			<?php
				$helion_woocommerce_sc = helion_get_theme_option( 'front_page_woocommerce_products' );
			if ( 'products' == $helion_woocommerce_sc ) {
				$helion_woocommerce_sc_ids      = helion_get_theme_option( 'front_page_woocommerce_products_per_page' );
				$helion_woocommerce_sc_per_page = count( explode( ',', $helion_woocommerce_sc_ids ) );
			} else {
				$helion_woocommerce_sc_per_page = max( 1, (int) helion_get_theme_option( 'front_page_woocommerce_products_per_page' ) );
			}
				$helion_woocommerce_sc_columns = max( 1, min( $helion_woocommerce_sc_per_page, (int) helion_get_theme_option( 'front_page_woocommerce_products_columns' ) ) );
				echo do_shortcode(
					"[{$helion_woocommerce_sc}"
									. ( 'products' == $helion_woocommerce_sc
											? ' ids="' . esc_attr( $helion_woocommerce_sc_ids ) . '"'
											: '' )
									. ( 'product_category' == $helion_woocommerce_sc
											? ' category="' . esc_attr( helion_get_theme_option( 'front_page_woocommerce_products_categories' ) ) . '"'
											: '' )
									. ( 'best_selling_products' != $helion_woocommerce_sc
											? ' orderby="' . esc_attr( helion_get_theme_option( 'front_page_woocommerce_products_orderby' ) ) . '"'
												. ' order="' . esc_attr( helion_get_theme_option( 'front_page_woocommerce_products_order' ) ) . '"'
											: '' )
									. ' per_page="' . esc_attr( $helion_woocommerce_sc_per_page ) . '"'
									. ' columns="' . esc_attr( $helion_woocommerce_sc_columns ) . '"'
					. ']'
				);
				?>
			</div>
		</div>
	</div>
</div>
