<?php
/* EssentialAddonsForElementorLite support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'helion_essential_addons_for_elementor_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'helion_essential_addons_for_elementor_theme_setup9', 9 );
	function helion_essential_addons_for_elementor_theme_setup9() {
		if ( helion_exists_essential_addons_for_elementor() ) {
			add_action( 'wp_enqueue_scripts', 'helion_essential_addons_for_elementor_frontend_scripts', 1100 );
            add_action( 'wp_enqueue_scripts', 'helion_essential_addons_for_elementor_responsive_styles', 2000 );
			add_filter( 'helion_filter_merge_styles', 'helion_essential_addons_for_elementor_merge_styles' );
            add_filter( 'helion_filter_merge_styles_responsive', 'helion_essential_addons_for_elementor_merge_styles_responsive' );
		}
		if ( is_admin() ) {
			add_filter( 'helion_filter_tgmpa_required_plugins', 'helion_essential_addons_for_elementor_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'helion_essential_addons_for_elementor_tgmpa_required_plugins' ) ) {
	
	function helion_essential_addons_for_elementor_tgmpa_required_plugins( $list = array() ) {
		if ( helion_storage_isset( 'required_plugins', 'essential-addons-for-elementor-lite' ) && helion_storage_get_array( 'required_plugins', 'essential-addons-for-elementor-lite', 'install' ) !== false ) {
			$list[] = array(
				'name'     => helion_storage_get_array( 'required_plugins', 'essential-addons-for-elementor-lite', 'title' ),
				'slug'     => 'essential-addons-for-elementor-lite',
				'required' => false,
			);
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( ! function_exists( 'helion_exists_essential_addons_for_elementor' ) ) {
	function helion_exists_essential_addons_for_elementor() {
        return class_exists( 'Elementor\Plugin' ) || defined( 'EAEL_PLUGIN_VERSION' );
	}
}

// Custom styles and scripts
//------------------------------------------------------------------------

// Enqueue styles for frontend
if ( ! function_exists( 'helion_essential_addons_for_elementor_frontend_scripts' ) ) {
	
	function helion_essential_addons_for_elementor_frontend_scripts() {
		if ( helion_is_on( helion_get_theme_option( 'debug_mode' ) ) ) {
			$helion_url = helion_get_file_url( 'plugins/essential-addons-for-elementor-lite/essential-addons-for-elementor-lite.css' );
			if ( '' != $helion_url ) {
				wp_enqueue_style( 'helion-essential-addons-for-elementor-lite', $helion_url, array(), null );
			}
		}
	}
}
// Enqueue responsive styles for frontend
if ( ! function_exists( 'helion_essential_addons_for_elementor_responsive_styles' ) ) {
    
    function helion_essential_addons_for_elementor_responsive_styles() {
        if ( helion_is_on( helion_get_theme_option( 'debug_mode' ) ) ) {
            $helion_url = helion_get_file_url( 'plugins/essential-addons-for-elementor-lite/essential-addons-for-elementor-lite-responsive.css' );
            if ( '' != $helion_url ) {
                wp_enqueue_style( 'helion-essential-addons-for-elementor-lite-responsive', $helion_url, array(), null );
            }
        }
    }
}

// Merge custom styles
if ( ! function_exists( 'helion_essential_addons_for_elementor_merge_styles' ) ) {
	
	function helion_essential_addons_for_elementor_merge_styles( $list ) {
		$list[] = 'plugins/essential-addons-for-elementor-lite/essential-addons-for-elementor-lite.css';
		return $list;
	}
}
// Merge responsive styles
if ( ! function_exists( 'helion_essential_addons_for_elementor_merge_styles_responsive' ) ) {
    
    function helion_essential_addons_for_elementor_merge_styles_responsive( $list ) {
        $list[] = 'plugins/essential-addons-for-elementor-lite/essential-addons-for-elementor-lite-responsive.css';
        return $list;
    }
}


// Add plugin-specific colors and fonts to the custom CSS
if ( helion_exists_essential_addons_for_elementor() ) {
	require_once HELION_THEME_DIR . 'plugins/essential-addons-for-elementor-lite/essential-addons-for-elementor-lite-styles.php';
}

