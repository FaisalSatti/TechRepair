<?php
/* WPBakery PageBuilder support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'helion_vc_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'helion_vc_theme_setup9', 9 );
	function helion_vc_theme_setup9() {

		if ( helion_exists_vc() ) {
		
			add_action( 'wp_enqueue_scripts', 'helion_vc_frontend_scripts', 1100 );
			add_action( 'wp_enqueue_scripts', 'helion_vc_responsive_styles', 2000 );
			add_filter( 'helion_filter_merge_styles', 'helion_vc_merge_styles' );
			add_filter( 'helion_filter_merge_styles_responsive', 'helion_vc_merge_styles_responsive' );

			// Add/Remove params in the standard VC shortcodes
			//-----------------------------------------------------
			add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'helion_vc_add_params_classes', 10, 3 );
			add_filter( 'vc_iconpicker-type-fontawesome', 'helion_vc_iconpicker_type_fontawesome' );

			// Color scheme
			$scheme  = array(
				'param_name'  => 'scheme',
				'heading'     => esc_html__( 'Color scheme', 'helion' ),
				'description' => wp_kses_data( __( 'Select color scheme to decorate this block', 'helion' ) ),
				'group'       => esc_html__( 'Colors', 'helion' ),
				'admin_label' => true,
				'value'       => array_flip( helion_get_list_schemes( true ) ),
				'type'        => 'dropdown',
			);
			$sc_list = apply_filters( 'helion_filter_add_scheme_in_vc', array( 'vc_section', 'vc_row', 'vc_row_inner', 'vc_column', 'vc_column_inner', 'vc_column_text' ) );
			foreach ( $sc_list as $sc ) {
				vc_add_param( $sc, $scheme );
			}

			// Load custom VC styles for blog archive page
			add_filter( 'helion_filter_blog_archive_start', 'helion_vc_add_inline_css' );
		}

		if ( is_admin() ) {
			add_filter( 'helion_filter_tgmpa_required_plugins', 'helion_vc_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'helion_vc_tgmpa_required_plugins' ) ) {
	
	function helion_vc_tgmpa_required_plugins( $list = array() ) {
		if ( helion_storage_isset( 'required_plugins', 'js_composer' ) && helion_storage_get_array( 'required_plugins', 'js_composer', 'install' ) !== false && helion_is_theme_activated() ) {
			$path = helion_get_plugin_source_path( 'plugins/js_composer/js_composer.zip' );
			if ( ! empty( $path ) || helion_get_theme_setting( 'tgmpa_upload' ) ) {
				$list[] = array(
					'name'     => helion_storage_get_array( 'required_plugins', 'js_composer', 'title' ),
					'slug'     => 'js_composer',
					'source'   => ! empty( $path ) ? $path : 'upload://js_composer.zip',
					'required' => false,
				);
			}
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( ! function_exists( 'helion_exists_vc' ) ) {
	function helion_exists_vc() {
		return class_exists( 'Vc_Manager' );
	}
}

// Check if plugin in frontend editor mode
if ( ! function_exists( 'helion_vc_is_frontend' ) ) {
	function helion_vc_is_frontend() {
		return ( isset( $_GET['vc_editable'] ) && 'true' == $_GET['vc_editable'] )
			|| ( isset( $_GET['vc_action'] ) && 'vc_inline' == $_GET['vc_action'] );
	}
}

// Enqueue styles for frontend
if ( ! function_exists( 'helion_vc_frontend_scripts' ) ) {
	
	function helion_vc_frontend_scripts() {
		if ( helion_is_on( helion_get_theme_option( 'debug_mode' ) ) ) {
			$helion_url = helion_get_file_url( 'plugins/js_composer/js_composer.css' );
			if ( '' != $helion_url ) {
				wp_enqueue_style( 'helion-vc', $helion_url, array(), null );
			}
		}
	}
}

// Enqueue responsive styles for frontend
if ( ! function_exists( 'helion_vc_responsive_styles' ) ) {
	
	function helion_vc_responsive_styles() {
		if ( helion_is_on( helion_get_theme_option( 'debug_mode' ) ) ) {
			$helion_url = helion_get_file_url( 'plugins/js_composer/js_composer-responsive.css' );
			if ( '' != $helion_url ) {
				wp_enqueue_style( 'helion-vc-responsive', $helion_url, array(), null );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'helion_vc_merge_styles' ) ) {
	
	function helion_vc_merge_styles( $list ) {
		$list[] = 'plugins/js_composer/js_composer.css';
		return $list;
	}
}

// Merge responsive styles
if ( ! function_exists( 'helion_vc_merge_styles_responsive' ) ) {
	
	function helion_vc_merge_styles_responsive( $list ) {
		$list[] = 'plugins/js_composer/js_composer-responsive.css';
		return $list;
	}
}

// Add VC custom styles to the inline CSS
if ( ! function_exists( 'helion_vc_add_inline_css' ) ) {
	
	function helion_vc_add_inline_css( $html ) {
		$vc_custom_css = get_post_meta( get_the_ID(), '_wpb_shortcodes_custom_css', true );
		if ( ! empty( $vc_custom_css ) ) {
			helion_add_inline_css( strip_tags( $vc_custom_css ) );
		}
		return $html;
	}
}



// Shortcodes support
//------------------------------------------------------------------------

// Add params to the standard VC shortcodes
if ( ! function_exists( 'helion_vc_add_params_classes' ) ) {
	
	function helion_vc_add_params_classes( $classes, $sc, $atts ) {
		// Add color scheme
		if ( in_array( $sc, apply_filters( 'helion_filter_add_scheme_in_vc', array( 'vc_section', 'vc_row', 'vc_row_inner', 'vc_column', 'vc_column_inner', 'vc_column_text' ) ) ) ) {
			if ( ! empty( $atts['scheme'] ) && ! helion_is_inherit( $atts['scheme'] ) ) {
				$classes .= ( $classes ? ' ' : '' ) . 'scheme_' . $atts['scheme'];
			}
		}
		return $classes;
	}
}

// Add theme icons to the VC iconpicker list
if ( ! function_exists( 'helion_vc_iconpicker_type_fontawesome' ) ) {
	
	function helion_vc_iconpicker_type_fontawesome( $icons ) {
		$list = helion_get_list_icons_classes();
		if ( ! is_array( $list ) || count( $list ) == 0 ) {
			return $icons;
		}
		$rez = array();
		foreach ( $list as $icon ) {
			$rez[] = array( $icon => str_replace( 'icon-', '', $icon ) );
		}
		return array_merge( $icons, array( esc_html__( 'Theme Icons', 'helion' ) => $rez ) );
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if ( helion_exists_vc() ) {
	require_once HELION_THEME_DIR . 'plugins/js_composer/js_composer-styles.php'; }

