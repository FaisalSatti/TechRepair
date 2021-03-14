<?php
/* Essential Grid support functions
------------------------------------------------------------------------------- */


// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'helion_essential_grid_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'helion_essential_grid_theme_setup9', 9 );
	function helion_essential_grid_theme_setup9() {
		if ( helion_exists_essential_grid() ) {
			add_action( 'wp_enqueue_scripts', 'helion_essential_grid_frontend_scripts', 1100 );
			add_filter( 'helion_filter_merge_styles', 'helion_essential_grid_merge_styles' );
		}
		if ( is_admin() ) {
			add_filter( 'helion_filter_tgmpa_required_plugins', 'helion_essential_grid_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'helion_essential_grid_tgmpa_required_plugins' ) ) {
	
	function helion_essential_grid_tgmpa_required_plugins( $list = array() ) {
		if ( helion_storage_isset( 'required_plugins', 'essential-grid' ) && helion_storage_get_array( 'required_plugins', 'essential-grid', 'install' ) !== false && helion_is_theme_activated() ) {
			$path = helion_get_plugin_source_path( 'plugins/essential-grid/essential-grid.zip' );
			if ( ! empty( $path ) || helion_get_theme_setting( 'tgmpa_upload' ) ) {
				$list[] = array(
					'name'     => helion_storage_get_array( 'required_plugins', 'essential-grid', 'title' ),
					'slug'     => 'essential-grid',
					'source'   => ! empty( $path ) ? $path : 'upload://essential-grid.zip',
					'version'  => '2.3.3',
					'required' => false,
				);
			}
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( ! function_exists( 'helion_exists_essential_grid' ) ) {
	function helion_exists_essential_grid() {
		return defined( 'EG_PLUGIN_PATH' );
	}
}

// Enqueue styles for frontend
if ( ! function_exists( 'helion_essential_grid_frontend_scripts' ) ) {
	
	function helion_essential_grid_frontend_scripts() {
		if ( helion_is_on( helion_get_theme_option( 'debug_mode' ) ) ) {
			$helion_url = helion_get_file_url( 'plugins/essential-grid/essential-grid.css' );
			if ( '' != $helion_url ) {
				wp_enqueue_style( 'helion-essential-grid', $helion_url, array(), null );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'helion_essential_grid_merge_styles' ) ) {
	
	function helion_essential_grid_merge_styles( $list ) {
		$list[] = 'plugins/essential-grid/essential-grid.css';
		return $list;
	}
}

