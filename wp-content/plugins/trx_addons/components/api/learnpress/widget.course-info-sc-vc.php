<?php
/**
 * Widget: LP Course Info (WPBakery support)
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6.62
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}



// Add [trx_widget_lp_course_info] in the VC shortcodes list
if (!function_exists('trx_addons_sc_widget_lp_course_info_add_in_vc')) {
	function trx_addons_sc_widget_lp_course_info_add_in_vc() {
		
		if (!trx_addons_exists_vc()) return;
		
		vc_lean_map("trx_widget_lp_course_info", 'trx_addons_sc_widget_lp_course_info_add_in_vc_params');
		class WPBakeryShortCode_Trx_widget_lp_course_info extends WPBakeryShortCode {}
	}
	add_action('init', 'trx_addons_sc_widget_lp_course_info_add_in_vc', 20);
}

// Return params
if (!function_exists('trx_addons_sc_widget_lp_course_info_add_in_vc_params')) {
	function trx_addons_sc_widget_lp_course_info_add_in_vc_params() {
		return apply_filters('trx_addons_sc_map', array(
				"base" => "trx_widget_lp_course_info",
				"name" => esc_html__("LP Course Info", 'trx_addons'),
				"description" => wp_kses_data( __("Insert widget with current course info", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_widget_lp_course_info',
				"class" => "trx_widget_lp_course_info",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array_merge(
					array(
						array(
							"param_name" => "title",
							"heading" => esc_html__("Widget title", 'trx_addons'),
							"description" => wp_kses_data( __("Title of the widget", 'trx_addons') ),
							"admin_label" => true,
							"type" => "textfield"
						)
					),
					trx_addons_vc_add_id_param()
				)
			), 'trx_widget_lp_course_info' );
	}
}
