<?php
/* ThemeREX Updater support functions
------------------------------------------------------------------------------- */


// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'helion_trx_updater_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'helion_trx_updater_theme_setup9', 9 );
	function helion_trx_updater_theme_setup9() {
		if ( is_admin() ) {
			add_filter( 'helion_filter_tgmpa_required_plugins', 'helion_trx_updater_tgmpa_required_plugins', 8 );
		}
	}
}

// Filter to add in the required plugins list
// Priority 8 is used to add this plugin before all other plugins
if ( ! function_exists( 'helion_trx_updater_tgmpa_required_plugins' ) ) {
	
	function helion_trx_updater_tgmpa_required_plugins( $list = array() ) {
		if ( helion_storage_isset( 'required_plugins', 'trx_updater' ) && helion_storage_get_array( 'required_plugins', 'trx_updater', 'install' ) !== false && helion_is_theme_activated() ) {
			$path = helion_get_plugin_source_path( 'plugins/trx_updater/trx_updater.zip' );
			if ( ! empty( $path ) || helion_get_theme_setting( 'tgmpa_upload' ) ) {
				$list[] = array(
					'name'     => helion_storage_get_array( 'required_plugins', 'trx_updater', 'title' ),
					'slug'     => 'trx_updater',
					'source'   => ! empty( $path ) ? $path : 'upload://trx_updater.zip',
					'required' => false,
				);
			}
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( ! function_exists( 'helion_exists_trx_updater' ) ) {
	function helion_exists_trx_updater() {
		return defined( 'TRX_UPDATER_VERSION' );
	}
}
