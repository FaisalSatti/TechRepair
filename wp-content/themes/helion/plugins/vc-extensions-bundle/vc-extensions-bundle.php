<?php
/* WPBakery PageBuilder Extensions Bundle support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'helion_vc_extensions_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'helion_vc_extensions_theme_setup9', 9 );
	function helion_vc_extensions_theme_setup9() {
		if ( helion_exists_vc() && helion_exists_vc_extensions() ) {
			add_action( 'wp_enqueue_scripts', 'helion_vc_extensions_frontend_scripts', 1100 );
			add_filter( 'helion_filter_merge_styles', 'helion_vc_extensions_merge_styles' );
		}
		if ( is_admin() ) {
			add_filter( 'helion_filter_tgmpa_required_plugins', 'helion_vc_extensions_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'helion_vc_extensions_tgmpa_required_plugins' ) ) {
	
	function helion_vc_extensions_tgmpa_required_plugins( $list = array() ) {
		if ( helion_storage_isset( 'required_plugins', 'vc-extensions-bundle' ) && helion_storage_get_array( 'required_plugins', 'vc-extensions-bundle', 'install' ) !== false && helion_is_theme_activated() ) {
			$path = helion_get_plugin_source_path( 'plugins/vc-extensions-bundle/vc-extensions-bundle.zip' );
			if ( ! empty( $path ) || helion_get_theme_setting( 'tgmpa_upload' ) ) {
				$list[] = array(
					'name'     => helion_storage_get_array( 'required_plugins', 'vc-extensions-bundle', 'title' ),
					'slug'     => 'vc-extensions-bundle',
					'source'   => ! empty( $path ) ? $path : 'upload://vc-extensions-bundle.zip',
					'required' => false,
				);
			}
		}
		return $list;
	}
}

// Check if VC Extensions installed and activated
if ( ! function_exists( 'helion_exists_vc_extensions' ) ) {
	function helion_exists_vc_extensions() {
		return class_exists( 'Vc_Manager' ) && class_exists( 'VC_Extensions_CQBundle' );
	}
}

// Enqueue styles for frontend
if ( ! function_exists( 'helion_vc_extensions_frontend_scripts' ) ) {
	
	function helion_vc_extensions_frontend_scripts() {
		if ( helion_is_on( helion_get_theme_option( 'debug_mode' ) ) ) {
			$helion_url = helion_get_file_url( 'plugins/vc-extensions-bundle/vc-extensions-bundle.css' );
			if ( '' != $helion_url ) {
				wp_enqueue_style( 'helion-vc-extensions-bundle', $helion_url, array(), null );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'helion_vc_extensions_merge_styles' ) ) {
	
	function helion_vc_extensions_merge_styles( $list ) {
		$list[] = 'plugins/vc-extensions-bundle/vc-extensions-bundle.css';
		return $list;
	}
}

