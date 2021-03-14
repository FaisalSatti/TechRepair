<?php
/* WP GDPR Compliance support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'helion_wp_gdpr_compliance_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'helion_wp_gdpr_compliance_theme_setup9', 9 );
	function helion_wp_gdpr_compliance_theme_setup9() {
		if ( is_admin() ) {
			add_filter( 'helion_filter_tgmpa_required_plugins', 'helion_wp_gdpr_compliance_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'helion_wp_gdpr_compliance_tgmpa_required_plugins' ) ) {
	
	function helion_wp_gdpr_compliance_tgmpa_required_plugins( $list = array() ) {
		if ( helion_storage_isset( 'required_plugins', 'wp-gdpr-compliance' ) && helion_storage_get_array( 'required_plugins', 'wp-gdpr-compliance', 'install' ) !== false ) {
			$list[] = array(
				'name'     => helion_storage_get_array( 'required_plugins', 'wp-gdpr-compliance', 'title' ),
				'slug'     => 'wp-gdpr-compliance',
				'required' => false,
			);
		}
		return $list;
	}
}

// Check if this plugin installed and activated
if ( ! function_exists( 'helion_exists_wp_gdpr_compliance' ) ) {
	function helion_exists_wp_gdpr_compliance() {
		return class_exists( 'WPGDPRC\WPGDPRC' );
	}
}
