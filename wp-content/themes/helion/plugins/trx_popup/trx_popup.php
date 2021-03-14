<?php
/* ThemeREX Popup support functions
------------------------------------------------------------------------------- */


// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'helion_trx_popup_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'helion_trx_popup_theme_setup9', 9 );
	function helion_trx_popup_theme_setup9() {
        if ( is_admin() ) {
            add_filter( 'helion_filter_tgmpa_required_plugins', 'helion_trx_popup_tgmpa_required_plugins' );
        }

        if (helion_exists_trx_popup()) {
            add_action('wp_enqueue_scripts', 'helion_trx_popup_frontend_styles', 1100);
            add_filter('helion_filter_merge_styles', 'helion_trx_popup_merge_styles');
        }

        // Add plugin-specific colors and fonts to the custom CSS
        if (helion_exists_trx_popup()) {
            require_once HELION_THEME_DIR . 'plugins/trx_popup/trx_popup-styles.php';
        }
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'helion_trx_popup_tgmpa_required_plugins' ) ) {
	
	function helion_trx_popup_tgmpa_required_plugins( $list = array() ) {
		if ( helion_storage_isset( 'required_plugins', 'trx_popup' ) && helion_storage_get_array( 'required_plugins', 'trx_popup', 'install' ) !== false && helion_is_theme_activated() ) {
			$path = helion_get_plugin_source_path( 'plugins/trx_popup/trx_popup.zip' );
			if ( ! empty( $path ) || helion_get_theme_setting( 'tgmpa_upload' ) ) {
				$list[] = array(
					'name'     => helion_storage_get_array( 'required_plugins', 'trx_popup', 'title' ),
					'slug'     => 'trx_popup',
					'source'   => ! empty( $path ) ? $path : 'upload://trx_popup.zip',
					'required' => false,
				);
			}
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( ! function_exists( 'helion_exists_trx_popup' ) ) {
	function helion_exists_trx_popup() {
		return defined( 'TRX_POPUP_URL' );
	}
}

// Enqueue custom scripts
if (!function_exists('helion_trx_popup_frontend_styles')) {
    
    function helion_trx_popup_frontend_styles()
    {
        if (helion_is_on(helion_get_theme_option('debug_mode'))) {
            $helion_url = helion_get_file_url('plugins/trx_popup/trx_popup.css');
            if ('' != $helion_url) {
                wp_enqueue_style( 'helion-trx-popup', $helion_url, array(), null);
            }
        }
    }
}

// Merge custom scripts
if (!function_exists('helion_trx_popup_merge_styles')) {
    
    function helion_trx_popup_merge_styles($list)
    {
        $list[] = 'plugins/trx_popup/trx_popup.css';
        return $list;
    }
}

// Popup color scheme
if ( ! function_exists( 'helion_skin_trx_popup_classes' ) ) {
    add_filter( 'trx_popup_filter_classes', 'helion_skin_trx_popup_classes' );
    function helion_skin_trx_popup_classes() {
        return 'scheme_dark';
    }
}
