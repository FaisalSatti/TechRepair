<?php
/* Elegro Crypto Payment support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'helion_elegro_payment_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'helion_elegro_payment_theme_setup9', 9 );
	function helion_elegro_payment_theme_setup9() {
		if ( is_admin() ) {
			add_filter( 'helion_filter_tgmpa_required_plugins', 'helion_elegro_payment_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'helion_elegro_payment_tgmpa_required_plugins' ) ) {
	
	function helion_elegro_payment_tgmpa_required_plugins( $list = array() ) {
		if ( helion_storage_isset( 'required_plugins', 'woocommerce' ) && helion_storage_isset( 'required_plugins', 'elegro-payment' ) && helion_storage_get_array( 'required_plugins', 'elegro-payment', 'install' ) !== false ) {
			$list[] = array(
				'name'     => helion_storage_get_array( 'required_plugins', 'elegro-payment', 'title' ),
				'slug'     => 'elegro-payment',
				'required' => false,
			);
		}
		return $list;
	}
}

// Check if this plugin installed and activated
if ( ! function_exists( 'helion_exists_elegro_payment' ) ) {
	function helion_exists_elegro_payment() {
		return class_exists( 'WC_Elegro_Payment' );
	}
}




