<?php

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'helion_yith_woocommerce_compare_theme_setup9' ) ) {
    add_action( 'after_setup_theme', 'helion_yith_woocommerce_compare_theme_setup9', 9 );
    function helion_yith_woocommerce_compare_theme_setup9() {

        

        if ( is_admin() ) {
            add_filter( 'helion_filter_tgmpa_required_plugins', 'helion_yith_woocommerce_compare_tgmpa_required_plugins' );
        }
    }
}

// Filter to add in the required plugins list
if ( ! function_exists( 'helion_yith_woocommerce_compare_tgmpa_required_plugins' ) ) {
    
    function helion_yith_woocommerce_compare_tgmpa_required_plugins( $list = array() ) {
        if ( helion_storage_isset( 'required_plugins', 'yith-woocommerce-compare' ) && helion_storage_get_array( 'required_plugins', 'yith-woocommerce-compare', 'install' ) !== false ) {
            $list[] = array(
                'name'     => helion_storage_get_array( 'required_plugins', 'yith-woocommerce-compare', 'title' ),
                'slug'     => 'yith-woocommerce-compare',
                'required' => false,
            );
        }
        return $list;
    }
}

// Check if plugin installed and activated
if ( ! function_exists( 'helion_exists_yith_woocommerce_compare' ) ) {
    function helion_exists_yith_woocommerce_compare() {
        return class_exists( 'YITH_WOOCOMPARE' );
    }
}



// Set plugin's specific importer options
if ( !function_exists( 'helion_yith_woocommerce_compare_importer_set_options' ) ) {
    if (is_admin()) add_filter( 'trx_addons_filter_importer_options',    'helion_yith_woocommerce_compare_importer_set_options' );
    function helion_yith_woocommerce_compare_importer_set_options($options=array()) {   
        if ( helion_exists_yith_woocommerce_compare() && in_array('yith-woocommerce-compare', $options['required_plugins']) ) {
            $options['additional_options'][]    = 'yith_woocompare_%';                    // Add slugs to export options for this plugin
           
            if (is_array($options['files']) && count($options['files']) > 0) {
                foreach ($options['files'] as $k => $v) {
                    $options['files'][$k]['file_with_yith-woocommerce-compare'] = str_replace('name.ext', 'yith-woocommerce-compare.txt', $v['file_with_']);
                }
            }
        }
        return $options;
    }
}
