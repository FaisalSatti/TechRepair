<?php
/**
 * Widget: Course info
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6.62
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}

// Load widget
if (!function_exists('trx_addons_widget_lp_course_info_load')) {
	add_action( 'widgets_init', 'trx_addons_widget_lp_course_info_load' );
	function trx_addons_widget_lp_course_info_load() {
		register_widget('trx_addons_widget_lp_course_info');
	}
}

// Widget Class
class trx_addons_widget_lp_course_info extends TRX_Addons_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_lp_course_info', 'description' => esc_html__('LearnPress course info', 'trx_addons'));
		parent::__construct( 'trx_addons_widget_lp_course_info', esc_html__('ThemeREX LP Course info', 'trx_addons'), $widget_ops );
	}

	// Show widget
	function widget($args, $instance) {
		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '');
		trx_addons_get_template_part(TRX_ADDONS_PLUGIN_API . 'learnpress/tpl.widget.course-info.php',
										'trx_addons_args_widget_lp_course_info',
										apply_filters('trx_addons_filter_widget_args',
											array_merge($args, compact('title')),
											$instance, 'trx_addons_widget_lp_course_info')
									);
	}

	// Update the widget settings.
	function update($new_instance, $instance) {
		$instance = array_merge($instance, $new_instance);
		$instance['title'] = strip_tags($new_instance['title']);
		return apply_filters('trx_addons_filter_widget_args_update', $instance, $new_instance, 'trx_addons_widget_lp_course_info');
	}

	// Displays the widget settings controls on the widget panel.
	function form($instance) {

		// Set up some default widget settings
		$instance = wp_parse_args( (array) $instance, apply_filters('trx_addons_filter_widget_args_default', array(
			'title' => ''
			), 'trx_addons_widget_lp_course_info')
		);
		
		do_action('trx_addons_action_before_widget_fields', $instance, 'trx_addons_widget_lp_course_info', $this);
		
		$this->show_field(array('name' => 'title',
								'title' => __('Widget title:', 'trx_addons'),
								'value' => $instance['title'],
								'type' => 'text'));
		
		do_action('trx_addons_action_after_widget_title', $instance, 'trx_addons_widget_lp_course_info', $this);
		
		do_action('trx_addons_action_after_widget_fields', $instance, 'trx_addons_widget_lp_course_info', $this);
	}
}



// Add shortcodes
//----------------------------------------------------------------------------
require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'learnpress/widget.course-info-sc.php';

// Add shortcodes to Elementor
if ( trx_addons_exists_elementor() && function_exists('trx_addons_elm_init') ) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'learnpress/widget.course-info-sc-elementor.php';
}

// Add shortcodes to VC
if ( trx_addons_exists_vc() && function_exists( 'trx_addons_vc_add_id_param' ) ) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'learnpress/widget.course-info-sc-vc.php';
}
